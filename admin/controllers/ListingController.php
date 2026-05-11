<?php
require_once BASE_PATH . '/core/Controller.php';
require_once BASE_PATH . '/shared/models/ListingModel.php';

class ListingController extends Controller
{
    private ListingModel $model;
    public function __construct() { $this->model = new ListingModel(); }

    // ── Active ads list ──────────────────────────────────────
    public function index(): void
    {
        $this->requireAuth();
        $filters = [
            'search'   => $this->sanitize($this->input('search', '')),
            'plan'     => $this->input('plan', ''),
            'city'     => $this->input('city', ''),
            'category' => $this->input('category', ''),
        ];
        $page  = max(1, (int) $this->input('page', 1));
        $scope = Auth::isCityAdmin() ? ['_city_scope' => Auth::cityId()] : [];
        $pager = $this->model->getAllWithRelations(array_merge($filters, ['status' => 'approved'], $scope), $page);
        $cities = Database::fetchAll("SELECT id, name FROM cities ORDER BY name");
        $cats   = Database::fetchAll("SELECT id, name FROM categories WHERE status='active' ORDER BY name");
        $this->view('listings.index', compact('pager', 'filters', 'cities', 'cats'));
    }

    // ── Expired ads list ─────────────────────────────────────
    public function expired(): void
    {
        $this->requireAuth();
        $page  = max(1, (int) $this->input('page', 1));
        [$cw, $cp] = $this->cityScope('bl.city_id');
        $sql   = "SELECT bl.*, u.name AS user_name, u.phone AS user_phone,
                         c.name AS city_name, cat.name AS category_name
                  FROM business_listings bl
                  LEFT JOIN users u   ON bl.user_id = u.id
                  LEFT JOIN cities c  ON bl.city_id = c.id
                  LEFT JOIN categories cat ON bl.category_id = cat.id
                  WHERE bl.status = 'approved'
                    AND u.plan_expires_at IS NOT NULL
                    AND u.plan_expires_at < CURDATE()
                    $cw
                  ORDER BY u.plan_expires_at DESC";
        $pager = Database::paginate($sql, $cp, $page, 20);
        $this->view('listings.expired', compact('pager'));
    }

    // ── Pending approvals ────────────────────────────────────
    public function pending(): void
    {
        $this->requireAuth();
        $page  = max(1, (int) $this->input('page', 1));
        $scope = Auth::isCityAdmin() ? ['_city_scope' => Auth::cityId()] : [];
        $pager = $this->model->getAllWithRelations(array_merge(['status' => 'pending'], $scope), $page);
        $this->view('listings.pending', compact('pager'));
    }

    // ── View listing detail ──────────────────────────────────
    public function show(string $id): void
    {
        $this->requireAuth();
        $listing = $this->model->getFullDetail((int) $id);
        if (!$listing) $this->redirect(BASE_URL . '/admin/listings');
        $images   = Database::fetchAll("SELECT * FROM listing_images WHERE listing_id=? ORDER BY sort_order", [(int)$id]);
        $services = Database::fetchAll("SELECT * FROM listing_services WHERE listing_id=? ORDER BY sort_order", [(int)$id]);
        $keywords = Database::fetchAll("SELECT k.name FROM listing_keywords lk JOIN keywords k ON lk.keyword_id=k.id WHERE lk.listing_id=?", [(int)$id]);
        $subcats  = Database::fetchAll("SELECT s.name FROM listing_subcategories ls JOIN subcategories s ON ls.subcategory_id=s.id WHERE ls.listing_id=?", [(int)$id]);
        $csrf     = $this->csrfToken();
        $this->view('listings.show', compact('listing','images','services','keywords','subcats','csrf'));
    }

    // ── Create listing — from user profile ───────────────────
    // URL: /admin/listings/create?user_id=X
    public function create(): void
    {
        $this->requireAuth();
        $userId = (int) $this->input('user_id');
        if (!$userId) $this->redirect(BASE_URL . '/admin/users');
        $user = Database::fetchOne(
            "SELECT u.*, pl.name AS plan_name, pl.label AS plan_label
             FROM users u LEFT JOIN plans pl ON u.plan_id=pl.id WHERE u.id=?", [$userId]
        );
        if (!$user) $this->redirect(BASE_URL . '/admin/users');
        if (Database::fetchOne("SELECT id FROM business_listings WHERE user_id=?", [$userId])) {
            Helper::flash('error', 'This user already has a listing.');
            $this->redirect(BASE_URL . '/admin/users/' . $userId);
        }
        $cats      = Database::fetchAll("SELECT id, name FROM categories WHERE status='active' ORDER BY name");
        $subcats   = Database::fetchAll("SELECT id, category_id, name FROM subcategories WHERE status='active' ORDER BY name");
        $cities    = Database::fetchAll("SELECT id, name FROM cities WHERE status='active' ORDER BY name");
        $keywords  = Database::fetchAll("SELECT k.*, c.name AS cat_name FROM keywords k LEFT JOIN categories c ON k.category_id=c.id WHERE k.status='active' ORDER BY k.name");
        $csrf      = $this->csrfToken();
        $this->view('listings.create', compact('user','cats','subcats','cities','keywords','csrf'));
    }

    public function store(): void
    {
        $this->requireAuth();
        $this->verifyCsrf();
        file_put_contents(BASE_PATH . '/assets/uploads/listings/debug.log', "STORE POST: " . print_r($_POST, true) . "\nFILES: " . print_r($_FILES, true) . "\n", FILE_APPEND);
        $userId = (int) $this->input('user_id');
        if (!$userId || Database::fetchOne("SELECT id FROM business_listings WHERE user_id=?", [$userId])) {
            Helper::flash('error', 'Invalid user or listing already exists.');
            $this->redirect(BASE_URL . '/admin/listings/create?user_id=' . $userId);
        }
        $businessName = $this->sanitize($this->input('business_name', ''));
        $planLevel    = $this->input('plan_level', 'basic');
        $status       = $this->input('status', 'approved');

        $listingId = $this->model->create([
            'user_id'           => $userId,
            'city_id'           => (int) $this->input('city_id') ?: null,
            'category_id'       => (int) $this->input('category_id') ?: null,
            'plan_level'        => $planLevel,
            'business_name'     => $businessName,
            'address'           => $this->sanitize($this->input('address', '')),
            'phone'             => $this->sanitize($this->input('phone', '')),
            'whatsapp'          => $this->sanitize($this->input('whatsapp', '')),
            'email'             => $this->sanitize($this->input('email', '')),
            'short_description' => $this->sanitize($this->input('short_description', '')),
            'website'           => $this->sanitize($this->input('website', '')),
            'map_embed'         => Helper::mapEmbedUrl($this->input('map_embed', '')),
            'facebook'          => $this->sanitize($this->input('facebook', '')),
            'instagram'         => $this->sanitize($this->input('instagram', '')),
            'youtube_url'       => $this->sanitize($this->input('youtube_url', '')),
            'slug'              => Helper::slug($businessName),
            'status'            => $status,
            'approved_by'       => $status === 'approved' ? Auth::id() : null,
            'approved_at'       => $status === 'approved' ? date('Y-m-d H:i:s') : null,
            'published_at'      => $status === 'approved' ? date('Y-m-d H:i:s') : null,
        ]);
        
        file_put_contents(BASE_PATH . '/assets/uploads/listings/debug.log', "Listing Created ID: $listingId\n", FILE_APPEND);

        // Handle Keywords
        if (!empty($_POST['keyword_ids'])) {
            foreach ($_POST['keyword_ids'] as $kid) {
                Database::execute("INSERT IGNORE INTO listing_keywords (listing_id, keyword_id) VALUES (?, ?)", [$listingId, (int)$kid]);
            }
        }

        // Handle Subcategories
        if (!empty($_POST['subcategory_ids'])) {
            foreach ($_POST['subcategory_ids'] as $sid) {
                Database::execute("INSERT IGNORE INTO listing_subcategories (listing_id, subcategory_id) VALUES (?, ?)", [$listingId, (int)$sid]);
            }
        }

        // Banner upload (pro)
        if (!empty($_FILES['top_banner']['name'])) {
            $f = Helper::uploadFile($_FILES['top_banner'], 'listings');
            if ($f) {
                $this->model->update($listingId, ['top_banner' => $f]);
                file_put_contents(BASE_PATH . '/assets/uploads/listings/debug.log', "Top Banner Uploaded: $f\n", FILE_APPEND);
            }
        }

        // Business Images upload (saves to gallery/listing_images)
        if (isset($_FILES['business_images']) && !empty($_FILES['business_images']['name'][0])) {
            $files = $_FILES['business_images'];
            file_put_contents(BASE_PATH . '/assets/uploads/listings/debug.log', "Processing " . count($files['name']) . " images\n", FILE_APPEND);
            for ($i = 0; $i < count($files['name']); $i++) {
                if ($files['error'][$i] === 0) {
                    $fArr = ['name'=>$files['name'][$i], 'type'=>$files['type'][$i], 'tmp_name'=>$files['tmp_name'][$i], 'error'=>$files['error'][$i], 'size'=>$files['size'][$i]];
                    $f = Helper::uploadFile($fArr, 'listings');
                    if ($f) {
                        try {
                            Database::execute("INSERT INTO listing_images (listing_id, filename, sort_order) VALUES (?, ?, ?)", [$listingId, $f, $i]);
                            file_put_contents(BASE_PATH . '/assets/uploads/listings/debug.log', "Inserted Image: $f for Listing: $listingId\n", FILE_APPEND);
                        } catch (Exception $e) {
                            file_put_contents(BASE_PATH . '/assets/uploads/listings/debug.log', "DB Image Insert Error: " . $e->getMessage() . "\n", FILE_APPEND);
                        }
                    } else {
                        file_put_contents(BASE_PATH . '/assets/uploads/listings/debug.log', "File Upload Failed for: " . $files['name'][$i] . "\n", FILE_APPEND);
                    }
                } else {
                    file_put_contents(BASE_PATH . '/assets/uploads/listings/debug.log', "File Error: " . $files['error'][$i] . " for index $i\n", FILE_APPEND);
                }
            }
        }

        // Update user plan to match listing plan
        $plan = Database::fetchOne("SELECT * FROM plans WHERE name=?", [$planLevel]);
        if ($plan && $plan['name'] !== 'free') {
            Database::execute(
                "UPDATE users SET plan_id=?, plan_expires_at=DATE_ADD(NOW(), INTERVAL ? DAY) WHERE id=?",
                [$plan['id'], $plan['duration_days'] ?? 365, $userId]
            );
        }

        $this->logActivity('create_listing', "Created listing: $businessName ($planLevel plan)", 'listing', $listingId);
        Helper::flash('success', 'Listing created and user plan updated to ' . ucfirst($planLevel) . '.');
        $this->redirect(BASE_URL . '/admin/users/' . $userId);
    }

    // ── Edit listing ─────────────────────────────────────────
    public function edit(string $id): void
    {
        $this->requireAuth();
        $listing  = $this->model->getFullDetail((int) $id);
        if (!$listing) $this->redirect(BASE_URL . '/admin/listings');
        $cats     = Database::fetchAll("SELECT id, name FROM categories WHERE status='active' ORDER BY name");
        $subcats  = Database::fetchAll("SELECT id, category_id, name FROM subcategories WHERE status='active' ORDER BY name");
        $cities   = Database::fetchAll("SELECT id, name FROM cities WHERE status='active' ORDER BY name");
        $keywords = Database::fetchAll("SELECT k.*, c.name AS cat_name FROM keywords k LEFT JOIN categories c ON k.category_id=c.id WHERE k.status='active' ORDER BY k.name");
        $selKwIds = array_column(Database::fetchAll("SELECT keyword_id FROM listing_keywords WHERE listing_id=?", [(int)$id]), 'keyword_id');
        $selScIds = array_column(Database::fetchAll("SELECT subcategory_id FROM listing_subcategories WHERE listing_id=?", [(int)$id]), 'subcategory_id');
        $csrf     = $this->csrfToken();
        $this->view('listings.edit', compact('listing','cats','subcats','cities','keywords','selKwIds','selScIds','csrf'));
    }

    public function updateListing(): void
    {
        $this->requireAuth();
        $this->verifyCsrf();
        file_put_contents(BASE_PATH . '/assets/uploads/listings/debug.log', "UPDATE POST: " . print_r($_POST, true) . "\nFILES: " . print_r($_FILES, true) . "\n", FILE_APPEND);
        $id = (int) $this->input('id');
        $listing = $this->model->find($id);
        if (!$listing) $this->redirect(BASE_URL . '/admin/listings');

        $businessName = $this->sanitize($this->input('business_name', ''));
        $this->model->update($id, [
            'business_name'     => $businessName,
            'category_id'       => (int) $this->input('category_id') ?: null,
            'city_id'           => (int) $this->input('city_id') ?: null,
            'address'           => $this->sanitize($this->input('address', '')),
            'phone'             => $this->sanitize($this->input('phone', '')),
            'whatsapp'          => $this->sanitize($this->input('whatsapp', '')),
            'email'             => $this->sanitize($this->input('email', '')),
            'short_description' => $this->sanitize($this->input('short_description', '')),
            'website'           => $this->sanitize($this->input('website', '')),
            'map_embed'         => Helper::mapEmbedUrl($this->input('map_embed', '')),
            'facebook'          => $this->sanitize($this->input('facebook', '')),
            'instagram'         => $this->sanitize($this->input('instagram', '')),
            'youtube_url'       => $this->sanitize($this->input('youtube_url', '')),
            'slug'              => Helper::slug($businessName) . '-' . $listing['user_id'],
        ]);

        // Banner upload (pro)
        if (!empty($_FILES['top_banner']['name'])) {
            $f = Helper::uploadFile($_FILES['top_banner'], 'listings');
            if ($f) $this->model->update($id, ['top_banner' => $f]);
        }

        // Business Image upload (multiple)
        if (isset($_FILES['business_images']) && !empty($_FILES['business_images']['name'][0])) {
            $files = $_FILES['business_images'];
            for ($i = 0; $i < count($files['name']); $i++) {
                if ($files['error'][$i] === 0) {
                    $fArr = ['name'=>$files['name'][$i], 'type'=>$files['type'][$i], 'tmp_name'=>$files['tmp_name'][$i], 'error'=>$files['error'][$i], 'size'=>$files['size'][$i]];
                    $f = Helper::uploadFile($fArr, 'listings');
                    if ($f) {
                        try {
                            Database::query("INSERT INTO listing_images (listing_id, filename, sort_order) VALUES (?, ?, ?)", [$id, $f, $i]);
                        } catch (Exception $e) {
                            file_put_contents(BASE_PATH . '/tmp/db_error.log', "Update Error: " . $e->getMessage() . "\n", FILE_APPEND);
                        }
                    }
                }
            }
        }

        // Update keywords
        Database::execute("DELETE FROM listing_keywords WHERE listing_id=?", [$id]);
        foreach ((array)($_POST['keyword_ids'] ?? []) as $kid) {
            Database::execute("INSERT IGNORE INTO listing_keywords (listing_id,keyword_id) VALUES (?,?)", [$id,(int)$kid]);
        }

        // Update subcategories
        Database::execute("DELETE FROM listing_subcategories WHERE listing_id=?", [$id]);
        foreach ((array)($_POST['subcategory_ids'] ?? []) as $sid) {
            Database::execute("INSERT IGNORE INTO listing_subcategories (listing_id, subcategory_id) VALUES (?, ?)", [$id, (int)$sid]);
        }

        Helper::flash('success', 'Listing updated.');
        $this->redirect(BASE_URL . '/admin/listings/' . $id);
    }

    // ── Suspend listing ──────────────────────────────────────
    public function suspend(): void
    {
        $this->requireAuth();
        $this->verifyCsrf();
        $id      = (int) $this->input('id');
        $listing = $this->model->find($id);
        if (!$listing) $this->redirect(BASE_URL . '/admin/listings');
        $newStatus = $listing['status'] === 'suspended' ? 'approved' : 'suspended';
        $this->model->update($id, ['status' => $newStatus]);
        Helper::flash('success', 'Listing ' . $newStatus . '.');
        $this->redirect(BASE_URL . '/admin/listings/' . $id);
    }

    public function approve(): void
    {
        $this->requireAuth();
        $this->verifyCsrf();
        $id = (int) $this->input('id');
        $this->model->update($id, [
            'status' => 'approved', 'approved_by' => Auth::id(),
            'approved_at' => date('Y-m-d H:i:s'), 'published_at' => date('Y-m-d H:i:s'),
        ]);
        Helper::flash('success', 'Listing approved.');
        $this->redirect(BASE_URL . '/admin/listings/' . $id);
    }

    public function reject(): void
    {
        $this->requireAuth();
        $this->verifyCsrf();
        $id = (int) $this->input('id');
        $this->model->update($id, ['status' => 'rejected', 'rejection_note' => $this->sanitize($this->input('rejection_note', ''))]);
        Helper::flash('info', 'Listing rejected.');
        $this->redirect(BASE_URL . '/admin/listings/' . $id);
    }

    public function delete(): void
    {
        $this->requireAuth();
        $this->verifyCsrf();
        $this->model->delete((int) $this->input('id'));
        Helper::flash('success', 'Listing deleted.');
        $this->redirect(BASE_URL . '/admin/listings');
    }
}
