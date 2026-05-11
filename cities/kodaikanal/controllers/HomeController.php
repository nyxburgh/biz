<?php
require_once __DIR__ . '/CityBaseController.php';

class HomeController extends CityBaseController
{
    public function index(): void
    {
        $cityId = CITY_ID;
        $categories = Database::fetchAll(
            "SELECT cat.*, COUNT(CASE WHEN COALESCE(pl.name, 'free') != 'free' THEN bl.id END) AS listing_count
             FROM categories cat
             LEFT JOIN business_listings bl ON bl.category_id = cat.id AND bl.city_id = ? AND bl.status = 'approved'
             LEFT JOIN users u ON bl.user_id = u.id
             LEFT JOIN plans pl ON u.plan_id = pl.id
             WHERE cat.status = 'active'
             GROUP BY cat.id
             HAVING listing_count > 0
             ORDER BY cat.sort_order, cat.name", [$cityId]
        );
        $banners = Database::fetchAll(
            "SELECT bl.*, (SELECT filename FROM listing_images WHERE listing_id = bl.id ORDER BY sort_order LIMIT 1) AS first_image,
             ROUND(AVG(r.rating),1) AS avg_rating, COUNT(r.id) AS review_count
             FROM business_listings bl
             LEFT JOIN users u ON bl.user_id = u.id
             LEFT JOIN plans pl ON u.plan_id = pl.id
             LEFT JOIN listing_reviews r ON r.listing_id = bl.id AND r.status = 'approved'
             WHERE bl.city_id = ? AND bl.plan_level IN ('pro','PRO') AND bl.status = 'approved' AND COALESCE(pl.name, 'free') != 'free'
             GROUP BY bl.id ORDER BY bl.published_at DESC LIMIT 6", [$cityId]
        );
        $featured = Database::fetchAll(
            "SELECT bl.*, cat.name AS cat_name,
             (SELECT filename FROM listing_images WHERE listing_id = bl.id ORDER BY sort_order LIMIT 1) AS first_image,
             ROUND(AVG(r.rating),1) AS avg_rating, COUNT(r.id) AS review_count
             FROM business_listings bl
             LEFT JOIN users u ON bl.user_id = u.id
             LEFT JOIN plans pl ON u.plan_id = pl.id
             LEFT JOIN categories cat ON bl.category_id = cat.id
             LEFT JOIN listing_reviews r ON r.listing_id = bl.id AND r.status = 'approved'
             WHERE bl.city_id = ? AND bl.plan_level IN ('premium','PREMIUM') AND bl.status = 'approved' AND COALESCE(pl.name, 'free') != 'free'
             GROUP BY bl.id ORDER BY bl.published_at DESC LIMIT 12", [$cityId]
        );
        $basics = Database::fetchAll(
            "SELECT bl.*, cat.name AS cat_name
             FROM business_listings bl
             LEFT JOIN users u ON bl.user_id = u.id
             LEFT JOIN plans pl ON u.plan_id = pl.id
             LEFT JOIN categories cat ON bl.category_id = cat.id
             WHERE bl.city_id = ? AND bl.plan_level = 'basic' AND bl.status = 'approved' AND COALESCE(pl.name, 'free') != 'free'
             ORDER BY bl.published_at DESC LIMIT 20", [$cityId]
        );
        $freeUsers = Database::fetchAll(
            "SELECT u.name, u.profession, u.phone
             FROM users u LEFT JOIN plans pl ON u.plan_id = pl.id
             WHERE (pl.name = 'free' OR u.plan_id IS NULL) AND u.city_id = ? AND u.status = 'active'
             ORDER BY u.created_at DESC LIMIT 50", [$cityId]
        );
        $this->view('home.index', compact('categories','banners','featured','basics','freeUsers'));
    }

    public function search(): void
    {
        $cityId = CITY_ID;
        $q      = $this->sanitize($this->input('q', ''));
        $catId  = (int) $this->input('cat', 0);
        $page   = max(1, (int) $this->input('page', 1));
        $w = ["bl.city_id = ?", "bl.status = 'approved'"]; $p = [$cityId];
        if ($q)     { $w[] = "(bl.business_name LIKE ? OR bl.short_description LIKE ? OR bl.address LIKE ?)"; $s = "%$q%"; array_push($p,$s,$s,$s); }
        if ($catId) { $w[] = "bl.category_id = ?"; $p[] = $catId; }
        $sql = "SELECT bl.*, cat.name AS cat_name,
                       (SELECT filename FROM listing_images WHERE listing_id = bl.id ORDER BY sort_order LIMIT 1) AS first_image,
                       ROUND(AVG(r.rating),1) AS avg_rating, COUNT(r.id) AS review_count
                FROM business_listings bl
                LEFT JOIN users u ON bl.user_id = u.id
                LEFT JOIN plans pl ON u.plan_id = pl.id
                LEFT JOIN categories cat ON bl.category_id = cat.id
                LEFT JOIN listing_reviews r ON r.listing_id = bl.id AND r.status = 'approved'
                WHERE " . implode(' AND ', $w) . " AND COALESCE(pl.name, 'free') != 'free'
                GROUP BY bl.id ORDER BY bl.plan_level DESC, bl.published_at DESC";
        $pager      = Database::paginate($sql, $p, $page, 10);
        $categories = Database::fetchAll("SELECT id, name FROM categories WHERE status = 'active' ORDER BY name");
        $this->view('search.index', compact('pager','q','catId','categories'));
    }
}
