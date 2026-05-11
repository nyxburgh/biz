<?php
require_once BASE_PATH . '/core/Controller.php';

class DashboardController extends Controller
{
    public function index(): void
    {
        $this->requireAuth();

        // Build city conditions with correct aliases per query
        $cityId   = Auth::isCityAdmin() ? Auth::cityId() : null;
        $uWhere   = $cityId ? "AND u.city_id = $cityId"  : '';   // users with alias
        $blWhere  = $cityId ? "AND bl.city_id = $cityId" : '';   // business_listings with alias
        $uPlain   = $cityId ? "AND city_id = $cityId"    : '';   // users no alias
        $blPlain  = $cityId ? "AND city_id = $cityId"    : '';   // listings no alias

        $stats = [
            'total_users'       => (int)(Database::fetchOne("SELECT COUNT(*) AS c FROM users WHERE 1=1 $uPlain")['c'] ?? 0),
            'active_users'      => (int)(Database::fetchOne("SELECT COUNT(*) AS c FROM users WHERE status='active' $uPlain")['c'] ?? 0),
            'total_listings'    => (int)(Database::fetchOne("SELECT COUNT(*) AS c FROM business_listings WHERE 1=1 $blPlain")['c'] ?? 0),
            'approved_listings' => (int)(Database::fetchOne("SELECT COUNT(*) AS c FROM business_listings WHERE status='approved' $blPlain")['c'] ?? 0),
            'pending_listings'  => (int)(Database::fetchOne("SELECT COUNT(*) AS c FROM business_listings bl WHERE bl.status='pending' $blPlain AND (bl.plan_level = 'free' OR EXISTS (SELECT 1 FROM payments p WHERE p.user_id = bl.user_id AND p.status = 'confirmed'))")['c'] ?? 0),
            'total_cities'      => Auth::isSuperAdmin()
                ? (int)(Database::fetchOne("SELECT COUNT(*) AS c FROM cities")['c'] ?? 0)
                : 1,
            'total_revenue'     => (float)(Database::fetchOne(
                "SELECT COALESCE(SUM(p.amount),0) AS s FROM payments p
                 LEFT JOIN users u ON p.user_id=u.id
                 WHERE p.status='confirmed' $uWhere")['s'] ?? 0),
            'pending_payments'  => (int)(Database::fetchOne(
                "SELECT COUNT(*) AS c FROM payments p
                 LEFT JOIN users u ON p.user_id=u.id
                 WHERE p.status='pending' $uWhere")['c'] ?? 0),

            'pending_reviews'   => (int)(Database::fetchOne(
                "SELECT COUNT(*) AS c FROM listing_reviews r
                 LEFT JOIN business_listings bl ON r.listing_id=bl.id
                 WHERE r.status='pending' $blWhere")['c'] ?? 0),

            'plan_stats' => Database::fetchAll(
                "SELECT pl.name, pl.label, COUNT(u.id) AS cnt
                 FROM plans pl
                 LEFT JOIN users u ON u.plan_id=pl.id " .
                ($cityId ? "AND u.city_id=$cityId" : "") .
                " GROUP BY pl.id ORDER BY pl.sort_order"
            ),

            'recent_users' => Database::fetchAll(
                "SELECT u.name, u.phone, u.status, u.created_at, pl.name AS plan
                 FROM users u LEFT JOIN plans pl ON u.plan_id=pl.id
                 WHERE 1=1 $uPlain
                 ORDER BY u.created_at DESC LIMIT 5"
            ),

            'recent_listings' => Database::fetchAll(
                "SELECT bl.business_name, bl.status, bl.created_at, u.name AS owner
                 FROM business_listings bl
                 LEFT JOIN users u ON bl.user_id=u.id
                 WHERE 1=1 $blWhere
                 ORDER BY bl.created_at DESC LIMIT 5"
            ),
        ];

        $actWhere    = $cityId ? "WHERE al.city_id=$cityId" : '';
        $activityLog = Database::fetchAll(
            "SELECT al.*, a.name AS admin_name
             FROM activity_logs al
             LEFT JOIN admins a ON al.actor_id=a.id AND al.actor_type='admin'
             $actWhere
             ORDER BY al.created_at DESC LIMIT 20"
        );

        $this->view('dashboard.index', compact('stats', 'activityLog'));
    }
}
