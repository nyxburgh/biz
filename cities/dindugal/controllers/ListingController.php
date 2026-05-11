<?php
require_once __DIR__ . '/CityBaseController.php';

class ListingController extends CityBaseController
{
    public function show(string $slug): void
    {
        // Skip system paths that might fall through
        $reserved = ['login','logout','dashboard','post-ad','edit-ad','upgrade','search','review','suggest-keyword','listing'];
        if (in_array($slug, $reserved)) {
            http_response_code(404); $this->view('errors.404', []); return;
        }

        $listing = Database::fetchOne(
            "SELECT bl.*, cat.name AS cat_name, c.name AS city_name,
                    ROUND(AVG(r.rating),1) AS avg_rating, COUNT(r.id) AS review_count
             FROM business_listings bl
             LEFT JOIN users u ON bl.user_id = u.id
             LEFT JOIN plans pl ON u.plan_id = pl.id
             LEFT JOIN categories cat ON bl.category_id = cat.id
             LEFT JOIN cities c ON bl.city_id = c.id
             LEFT JOIN listing_reviews r ON r.listing_id = bl.id AND r.status = 'approved'
             WHERE bl.slug = ? AND bl.city_id = ? AND bl.plan_level != 'free'
               AND COALESCE(pl.name, 'free') != 'free'
               AND bl.status IN ('approved','pending','archived')
             GROUP BY bl.id",
            [$slug, CITY_ID]
        );

        if (!$listing) { http_response_code(404); $this->view('errors.404', []); return; }

        // Increment views only for approved listings
        if ($listing['status'] === 'approved') {
            Database::execute("UPDATE business_listings SET views=views+1 WHERE id=?", [$listing['id']]);
        }

        $images   = Database::fetchAll("SELECT * FROM listing_images WHERE listing_id=? ORDER BY sort_order", [$listing['id']]);
        $services = Database::fetchAll("SELECT * FROM listing_services WHERE listing_id=? ORDER BY sort_order", [$listing['id']]);
        $keywords = Database::fetchAll(
            "SELECT k.name FROM listing_keywords lk JOIN keywords k ON lk.keyword_id=k.id WHERE lk.listing_id=?",
            [$listing['id']]
        );
        $reviews  = Database::fetchAll(
            "SELECT * FROM listing_reviews WHERE listing_id=? AND status='approved' ORDER BY created_at DESC",
            [$listing['id']]
        );
        $related  = Database::fetchAll(
            "SELECT bl.*, ROUND(AVG(r.rating),1) AS avg_rating FROM business_listings bl
             LEFT JOIN users u ON bl.user_id = u.id
             LEFT JOIN plans pl ON u.plan_id = pl.id
             LEFT JOIN listing_reviews r ON r.listing_id=bl.id AND r.status='approved'
             WHERE bl.category_id=? AND bl.id!=? AND bl.city_id=? AND bl.status='approved'
               AND bl.plan_level != 'free' AND COALESCE(pl.name, 'free') != 'free'
             GROUP BY bl.id ORDER BY bl.plan_level DESC LIMIT 4",
            [$listing['category_id'], $listing['id'], CITY_ID]
        );

        $isLoggedIn  = !empty($_SESSION['user_id']);
        $currentUser = $isLoggedIn ? ($_SESSION['user_data'] ?? null) : null;
        $hasReviewed = $isLoggedIn && Database::fetchOne(
            "SELECT id FROM listing_reviews WHERE listing_id=? AND user_id=?",
            [$listing['id'], $_SESSION['user_id']]
        );

        // QR code URL (Google Charts API — no library needed)
        $listingUrl  = CITY_URL . '/listing/' . urlencode($slug);
        $qrUrl       = 'https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=' . urlencode($listingUrl);

        $csrf = $this->csrfToken();
        $this->view('listing.show', compact(
            'listing','images','services','keywords','reviews','related',
            'isLoggedIn','currentUser','hasReviewed','csrf','qrUrl','listingUrl'
        ));
    }
}
