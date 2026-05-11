<?php
require_once __DIR__ . '/CityBaseController.php';

class UserController extends CityBaseController
{
    public function dashboard(): void
    {
        $this->requireUserAuth();
        $user = $this->currentUser();
        $listing = null;
        if (($user['plan_name'] ?? 'free') !== 'free') {
            $listing = Database::fetchOne(
                "SELECT bl.*, cat.name AS cat_name, ROUND(AVG(r.rating),1) AS avg_rating, COUNT(r.id) AS review_count
                 FROM business_listings bl
                 LEFT JOIN categories cat ON bl.category_id = cat.id
                 LEFT JOIN listing_reviews r ON r.listing_id = bl.id AND r.status = 'approved'
                 WHERE bl.user_id = ? GROUP BY bl.id",
                [$user['id']]
            );
        }
        $reviews  = $listing ? Database::fetchAll("SELECT * FROM listing_reviews WHERE listing_id = ? AND status = 'approved' ORDER BY created_at DESC LIMIT 10", [$listing['id']]) : [];
        $payments = Database::fetchAll("SELECT p.*, pl.label FROM payments p LEFT JOIN plans pl ON p.plan_id = pl.id WHERE p.user_id = ? ORDER BY p.created_at DESC", [$user['id']]);
        $plans    = Database::fetchAll("SELECT * FROM plans WHERE name != 'free' AND status = 'active' ORDER BY sort_order");
        $csrf     = $this->csrfToken();
        $cityUrl  = CITY_URL;
        $this->view('user.dashboard', compact('user','listing','reviews','payments','plans','csrf','cityUrl'));
    }

    public function postAd(): void
    {
        $this->requireUserAuth();
        $user = $this->currentUser();
        if (Database::fetchOne("SELECT id FROM business_listings WHERE user_id = ?", [$user['id']])) {
            Helper::flash('info', 'You already have a listing. Edit it from your dashboard.');
            $this->redirect(CITY_URL . '/dashboard');
        }
        $categories   = Database::fetchAll("SELECT id, name FROM categories WHERE status = 'active' ORDER BY name");
        $keywords     = Database::fetchAll("SELECT k.*, c.name AS cat_name FROM keywords k LEFT JOIN categories c ON k.category_id = c.id WHERE k.status = 'active' ORDER BY k.name");
        $subcategories = Database::fetchAll("SELECT id, category_id, name FROM subcategories WHERE status = 'active' ORDER BY name");
        // All plans including free
        $allPlans   = Database::fetchAll("SELECT * FROM plans WHERE status = 'active' ORDER BY sort_order");
        // Non-free plans for upgrade display
        $plans      = array_filter($allPlans, function($p){ return $p['name'] !== 'free'; });
        // Collect plan IDs that already have a confirmed payment for this user (no double-charge)
        $confirmedPlanIds = array_column(
            Database::fetchAll("SELECT plan_id FROM payments WHERE user_id = ? AND status = 'confirmed'", [$user['id']]),
            'plan_id'
        );
        $csrf = $this->csrfToken();
        $cityUrl = CITY_URL;
        $this->view('user.post-ad', compact('user','categories','subcategories','keywords','allPlans','plans','confirmedPlanIds','csrf','cityUrl'));
    }

    public function updateProfile(): void
    {
        $this->requireUserAuth();
        $this->verifyCsrf();

        $user = $this->currentUser();
        $name = $this->sanitize($this->input('name', ''));
        $email = $this->sanitize($this->input('email', ''));
        $profession = $this->sanitize($this->input('profession', ''));

        if ($name === '') {
            Helper::flash('error', 'Name is required.');
            $this->redirect(CITY_URL . '/dashboard');
        }

        if ($email !== '' && Database::fetchOne(
            "SELECT id FROM users WHERE email = ? AND id != ?",
            [$email, $user['id']]
        )) {
            Helper::flash('error', 'Email is already registered to another account.');
            $this->redirect(CITY_URL . '/dashboard');
        }

        Database::execute(
            "UPDATE users SET name = ?, email = ?, profession = ?, updated_at = NOW() WHERE id = ?",
            [$name, $email !== '' ? $email : null, $profession, $user['id']]
        );

        $freshUser = Database::fetchOne("SELECT * FROM users WHERE id = ?", [$user['id']]);
        if ($freshUser) {
            $_SESSION['user_data'] = $freshUser;
        }

        Helper::flash('success', 'Profile updated successfully.');
        $this->redirect(CITY_URL . '/dashboard');
    }

    public function submitAd(): void
    {
        $this->requireUserAuth();
        $this->verifyCsrf();
        $user = $this->currentUser();
        $existingListing = Database::fetchOne("SELECT id FROM business_listings WHERE user_id = ?", [$user['id']]);

        $planLevel = $this->input('plan_level', 'free');
        $plan = Database::fetchOne("SELECT * FROM plans WHERE name = ?", [$planLevel]);

        // FREE plan: no business listing created — just ensure user is on free plan
        if ($planLevel === 'free' || ($plan && $plan['price'] == 0 && $plan['name'] === 'free')) {
            if ($existingListing) {
                Database::execute("DELETE FROM business_listings WHERE id = ?", [$existingListing['id']]);
            }
            // Update user to free plan if not already
            $freePlan = Database::fetchOne("SELECT id FROM plans WHERE name = 'free'", []);
            if ($freePlan) {
                Database::execute(
                    "UPDATE users SET plan_id = ?, plan_expires_at = NULL, updated_at = NOW() WHERE id = ?",
                    [$freePlan['id'], $user['id']]
                );
                $freshUser = $this->currentUser();
                if ($freshUser) $_SESSION['user_data'] = $freshUser;
            }
            Helper::flash('success', 'You are registered as a Free member. Your profile appears in the sidebar listing.');
            $this->redirect(CITY_URL . '/dashboard');
        }

        if ($existingListing) {
            Helper::flash('error', 'You already have a listing.');
            $this->redirect(CITY_URL . '/dashboard');
        }

        // Paid plans: create listing
        $businessName = $this->sanitize($this->input('business_name', ''));
        if ($businessName === '') {
            Helper::flash('error', 'Business name is required for paid plans.');
            $this->redirect(CITY_URL . '/post-ad');
        }
        $banner = null;
        if (!empty($_FILES['top_banner']['name']) && in_array($planLevel, ['pro'])) {
            $banner = Helper::uploadFile($_FILES['top_banner'], 'listings');
        }

        Database::execute(
            "INSERT INTO business_listings (user_id,city_id,category_id,plan_level,business_name,address,phone,whatsapp,email,short_description,website,map_embed,facebook,instagram,youtube_url,top_banner,slug,status,created_at)
             VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,NOW())",
            [$user['id'],CITY_ID,(int)$this->input('category_id')?:null,$planLevel,$businessName,
             $this->sanitize($this->input('address','')),
             $this->sanitize($this->input('phone',$user['phone'])),
             $this->sanitize($this->input('whatsapp','')),
             $this->sanitize($this->input('email',$user['email']??'')),
             $this->sanitize($this->input('short_description','')),
             in_array($planLevel,['premium','pro']) ? $this->sanitize($this->input('website','')) : '',
             Helper::mapEmbedUrl($this->input('map_embed','')),
             in_array($planLevel,['premium','pro']) ? $this->sanitize($this->input('facebook','')) : '',
             in_array($planLevel,['premium','pro']) ? $this->sanitize($this->input('instagram','')) : '',
             $planLevel === 'pro' ? $this->sanitize($this->input('youtube_url','')) : '',
             $banner,
             self::uniqueSlug($businessName, CITY_ID), 'pending']
        );
        $listingId = Database::lastInsertId();

        // Keywords (premium+)
        if (in_array($planLevel, ['premium','pro'])) {
            foreach ((array)($_POST['keyword_ids']??[]) as $kid) {
                Database::execute("INSERT IGNORE INTO listing_keywords (listing_id,keyword_id) VALUES (?,?)", [$listingId,(int)$kid]);
            }
        }

        // Images (premium+)
        if (in_array($planLevel, ['premium','pro']) && !empty($_FILES['listing_images']['name'][0])) {
            $sortOrder = 0;
            foreach ($_FILES['listing_images']['tmp_name'] as $idx => $tmpName) {
                if (empty($tmpName) || $_FILES['listing_images']['error'][$idx] !== UPLOAD_ERR_OK) continue;
                $singleFile = [
                    'name'     => $_FILES['listing_images']['name'][$idx],
                    'type'     => $_FILES['listing_images']['type'][$idx],
                    'tmp_name' => $tmpName,
                    'error'    => $_FILES['listing_images']['error'][$idx],
                    'size'     => $_FILES['listing_images']['size'][$idx],
                ];
                $filename = Helper::uploadFile($singleFile, 'listings');
                if ($filename) {
                    Database::execute(
                        "INSERT INTO listing_images (listing_id,filename,sort_order) VALUES (?,?,?)",
                        [$listingId, $filename, $sortOrder++]
                    );
                }
                if ($sortOrder >= 5) break; // max 5 images
            }
        }

        // Services (pro)
        if ($planLevel === 'pro') {
            $titles = (array)($_POST['service_titles'] ?? []);
            $prices = (array)($_POST['service_prices'] ?? []);
            foreach ($titles as $i => $title) {
                $title = trim($title);
                if (!$title) continue;
                Database::execute(
                    "INSERT INTO listing_services (listing_id,title,price,sort_order) VALUES (?,?,?,?)",
                    [$listingId, $title, trim($prices[$i] ?? ''), $i]
                );
            }
        }

        // Payment
        if ($plan && $plan['price'] > 0) {
            $confirmedPayment = Database::fetchOne(
                "SELECT id FROM payments WHERE user_id = ? AND plan_id = ? AND status = 'confirmed'",
                [$user['id'], $plan['id']]
            );
            if (!$confirmedPayment) {
                $proof = null;
                if (!empty($_FILES['payment_proof']['name'])) $proof = Helper::uploadFile($_FILES['payment_proof'], 'payments');
                Database::execute("INSERT INTO payments (user_id,plan_id,amount,payment_mode,reference,payment_proof,status,created_at) VALUES (?,?,?,?,?,?,'pending',NOW())",
                    [$user['id'],$plan['id'],$plan['price'],$this->sanitize($this->input('payment_mode','')),
                     $this->sanitize($this->input('reference','')),$proof]);
            }
        }

        Helper::flash('success', 'Listing submitted for approval!');
        $this->redirect(CITY_URL . '/dashboard');
    }

    public function editAd(): void
    {
        $this->requireUserAuth();
        $user    = $this->currentUser();
        if (($user['plan_name'] ?? 'free') === 'free') {
            $this->redirect(CITY_URL . '/dashboard');
        }
        $listing = Database::fetchOne("SELECT * FROM business_listings WHERE user_id = ?", [$user['id']]);
        if (!$listing) $this->redirect(CITY_URL . '/dashboard');
        $currentImages = Database::fetchAll("SELECT * FROM listing_images WHERE listing_id = ? ORDER BY sort_order", [$listing['id']]);
        $categories    = Database::fetchAll("SELECT id, name FROM categories WHERE status = 'active' ORDER BY name");
        $keywords      = Database::fetchAll("SELECT k.*, c.name AS cat_name FROM keywords k LEFT JOIN categories c ON k.category_id = c.id WHERE k.status = 'active' ORDER BY k.name");
        $selKwIds      = array_column(Database::fetchAll("SELECT keyword_id FROM listing_keywords WHERE listing_id = ?", [$listing['id']]), 'keyword_id');
        $csrf          = $this->csrfToken();
        $cityUrl       = CITY_URL;
        $this->view('user.edit-ad', compact('user','listing','categories','keywords','selKwIds','csrf','currentImages','cityUrl'));
    }

    public function updateAd(): void
    {
        $this->requireUserAuth();
        $this->verifyCsrf();
        $user    = $this->currentUser();
        if (($user['plan_name'] ?? 'free') === 'free') {
            $this->redirect(CITY_URL . '/dashboard');
        }
        $listing = Database::fetchOne("SELECT * FROM business_listings WHERE user_id = ?", [$user['id']]);
        if (!$listing) $this->redirect(CITY_URL . '/dashboard');

        $planLevel = $listing['plan_level'] ?? 'basic';
        $website   = in_array($planLevel, ['premium', 'pro'], true) ? $this->sanitize($this->input('website', '')) : '';
        $facebook  = in_array($planLevel, ['premium', 'pro'], true) ? $this->sanitize($this->input('facebook', '')) : '';
        $instagram = in_array($planLevel, ['premium', 'pro'], true) ? $this->sanitize($this->input('instagram', '')) : '';
        $youtube   = $planLevel === 'pro' ? $this->sanitize($this->input('youtube_url', '')) : '';

        $banner = $listing['top_banner'];
        if (!empty($_FILES['top_banner']['name']) && $planLevel === 'pro') {
            $newBanner = Helper::uploadFile($_FILES['top_banner'], 'listings');
            if ($newBanner) $banner = $newBanner;
        }

        Database::execute(
            "UPDATE business_listings SET category_id=?, business_name=?, address=?, phone=?, whatsapp=?, email=?, short_description=?, website=?, map_embed=?, facebook=?, instagram=?, youtube_url=?, top_banner=?, status='pending', updated_at=NOW() WHERE id=?",
            [(int)$this->input('category_id') ?: null,
             $this->sanitize($this->input('business_name','')), $this->sanitize($this->input('address','')),
             $this->sanitize($this->input('phone','')), $this->sanitize($this->input('whatsapp','')),
             $this->sanitize($this->input('email','')), $this->sanitize($this->input('short_description','')),
             $website, Helper::mapEmbedUrl($this->input('map_embed','')), $facebook, $instagram, $youtube, $banner, $listing['id']]
        );
        Database::execute("DELETE FROM listing_keywords WHERE listing_id = ?", [$listing['id']]);
        if (in_array($planLevel, ['premium', 'pro'], true)) {
            foreach ((array)($_POST['keyword_ids']??[]) as $kid) {
                Database::execute("INSERT IGNORE INTO listing_keywords (listing_id,keyword_id) VALUES (?,?)", [$listing['id'],(int)$kid]);
            }
        }
        if (in_array($planLevel, ['premium', 'pro'], true) && !empty($_FILES['listing_images']['name'][0])) {
            $currentCount = Database::fetchOne("SELECT COUNT(*) as c FROM listing_images WHERE listing_id=?", [$listing['id']])['c'] ?? 0;
            $sortOrder = $currentCount;
            foreach ($_FILES['listing_images']['tmp_name'] as $idx => $tmpName) {
                if ($sortOrder >= 5) break; // max 5 images
                if (empty($tmpName) || $_FILES['listing_images']['error'][$idx] !== UPLOAD_ERR_OK) continue;
                $singleFile = ['name' => $_FILES['listing_images']['name'][$idx], 'type' => $_FILES['listing_images']['type'][$idx], 'tmp_name' => $tmpName, 'error' => $_FILES['listing_images']['error'][$idx], 'size' => $_FILES['listing_images']['size'][$idx]];
                $filename = Helper::uploadFile($singleFile, 'listings');
                if ($filename) { Database::execute("INSERT INTO listing_images (listing_id,filename,sort_order) VALUES (?,?,?)", [$listing['id'], $filename, $sortOrder++]); }
            }
        }
        Helper::flash('success', 'Listing updated and sent for re-approval.');
        $this->redirect(CITY_URL . '/dashboard');
    }

    public function upgradePlan(): void
    {
        $this->requireUserAuth();
        $user  = $this->currentUser();
        $plans = Database::fetchAll("SELECT * FROM plans WHERE name != 'free' AND status = 'active' ORDER BY sort_order");
        $csrf  = $this->csrfToken();
        $cityUrl = CITY_URL;
        $this->view('user.upgrade', compact('user','plans','csrf','cityUrl'));
    }

    public function submitUpgrade(): void
    {
        $this->requireUserAuth();
        $this->verifyCsrf();
        $user   = $this->currentUser();
        $planId = (int) $this->input('plan_id');
        $plan   = Database::fetchOne("SELECT * FROM plans WHERE id = ?", [$planId]);
        if (!$plan || $plan['name'] === 'free') { Helper::flash('error','Invalid plan.'); $this->redirect(CITY_URL.'/upgrade'); }
        $proof = null;
        if (!empty($_FILES['payment_proof']['name'])) $proof = Helper::uploadFile($_FILES['payment_proof'], 'payments');
        Database::execute("INSERT INTO payments (user_id,plan_id,amount,payment_mode,reference,payment_proof,status,created_at) VALUES (?,?,?,?,?,?,'pending',NOW())",
            [$user['id'],$plan['id'],$plan['price'],$this->sanitize($this->input('payment_mode','')),
             $this->sanitize($this->input('reference','')),$proof]);
        Helper::flash('success', 'Payment submitted. Admin will confirm shortly.');
        $this->redirect(CITY_URL . '/dashboard');
    }

    public function submitReview(): void
    {
        $this->requireUserAuth();
        $this->verifyCsrf();
        $user      = $this->currentUser();
        $listingId = (int) $this->input('listing_id');
        $listing   = Database::fetchOne("SELECT user_id FROM business_listings WHERE id = ?", [$listingId]);
        if (!$listing || $listing['user_id'] == $user['id']) { $this->json(['error' => 'Cannot review your own listing.'], 403); }
        if (Database::fetchOne("SELECT id FROM listing_reviews WHERE listing_id = ? AND user_id = ?", [$listingId, $user['id']])) {
            $this->json(['error' => 'You have already reviewed this listing.'], 409);
        }
        Database::execute(
            "INSERT INTO listing_reviews (listing_id,user_id,reviewer_name,reviewer_phone,rating,comment,status,ip_address,created_at) VALUES (?,?,?,?,?,?,'pending',?,NOW())",
            [$listingId,$user['id'],$user['name'],$user['phone'],(int)$this->input('rating',5),$this->sanitize($this->input('comment','')), $_SERVER['REMOTE_ADDR']??null]
        );
        $this->json(['success' => true, 'message' => 'Review submitted for approval.']);
    }

    public function suggestKeyword(): void
    {
        $this->requireUserAuth();
        $this->verifyCsrf();
        $user    = $this->currentUser();
        $keyword = $this->sanitize($this->input('keyword', ''));
        if (!$keyword) $this->json(['error' => 'Keyword required.'], 422);
        Database::execute("INSERT INTO keyword_suggestions (user_id,keyword,category_id,status,created_at) VALUES (?,?,?,'pending',NOW())",
            [$user['id'], $keyword, (int)$this->input('category_id')?:null]);
        $this->json(['success' => true]);
    }

    private static function uniqueSlug(string $businessName, int $cityId): string
    {
        $base = \Helper::slug($businessName);
        if (!$base) $base = 'listing';
        $slug = $base;
        $i = 2;
        while (\Database::fetchOne("SELECT id FROM business_listings WHERE slug = ? AND city_id = ?", [$slug, $cityId])) {
            $slug = $base . '-' . $i++;
        }
        return $slug;
    }
}
