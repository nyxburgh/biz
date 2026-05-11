<?php
class ReportModel extends Model
{
    protected string $table = 'users';

    public function getDashboardStats(): array
    {
        return [
            'total_users'       => (int)(Database::fetchOne("SELECT COUNT(*) AS c FROM users")['c'] ?? 0),
            'active_users'      => (int)(Database::fetchOne("SELECT COUNT(*) AS c FROM users WHERE status='active'")['c'] ?? 0),
            'pending_users'     => (int)(Database::fetchOne("SELECT COUNT(*) AS c FROM users WHERE status='pending'")['c'] ?? 0),
            'total_listings'    => (int)(Database::fetchOne("SELECT COUNT(*) AS c FROM business_listings")['c'] ?? 0),
            'approved_listings' => (int)(Database::fetchOne("SELECT COUNT(*) AS c FROM business_listings WHERE status='approved'")['c'] ?? 0),
            'pending_listings'  => (int)(Database::fetchOne("SELECT COUNT(*) AS c FROM business_listings WHERE status='pending'")['c'] ?? 0),
            'total_cities'      => (int)(Database::fetchOne("SELECT COUNT(*) AS c FROM cities")['c'] ?? 0),
            'total_categories'  => (int)(Database::fetchOne("SELECT COUNT(*) AS c FROM categories")['c'] ?? 0),
            'total_revenue'     => (float)(Database::fetchOne("SELECT COALESCE(SUM(amount),0) AS s FROM payments WHERE status='confirmed'")['s'] ?? 0),
            'pending_payments'  => (int)(Database::fetchOne("SELECT COUNT(*) AS c FROM payments WHERE status='pending'")['c'] ?? 0),
            'kw_suggestions'    => (int)(Database::fetchOne("SELECT COUNT(*) AS c FROM keyword_suggestions WHERE status='pending'")['c'] ?? 0),
            'plan_stats'        => Database::fetchAll("SELECT pl.name, pl.label, COUNT(u.id) AS cnt FROM plans pl LEFT JOIN users u ON u.plan_id=pl.id GROUP BY pl.id ORDER BY pl.sort_order"),
            'recent_users'      => Database::fetchAll("SELECT u.name, u.phone, u.status, u.created_at, pl.label AS plan FROM users u LEFT JOIN plans pl ON u.plan_id=pl.id ORDER BY u.created_at DESC LIMIT 5"),
            'recent_listings'   => Database::fetchAll("SELECT bl.business_name, bl.status, bl.created_at, u.name AS owner FROM business_listings bl LEFT JOIN users u ON bl.user_id=u.id ORDER BY bl.created_at DESC LIMIT 5"),
        ];
    }

    public function getRegistrationChart(int $days = 30): array
    {
        return Database::fetchAll(
            "SELECT DATE(created_at) AS day, COUNT(*) AS cnt
             FROM users
             WHERE created_at >= DATE_SUB(NOW(), INTERVAL ? DAY)
             GROUP BY DATE(created_at) ORDER BY day ASC",
            [$days]
        );
    }

    public function getCityReport(): array
    {
        return Database::fetchAll(
            "SELECT c.name, c.slug,
                    COUNT(DISTINCT u.id) AS users,
                    COUNT(DISTINCT bl.id) AS listings
             FROM cities c
             LEFT JOIN users u ON u.city_id = c.id
             LEFT JOIN business_listings bl ON bl.city_id = c.id
             GROUP BY c.id ORDER BY users DESC"
        );
    }

    public function getPlanReport(): array
    {
        return Database::fetchAll(
            "SELECT pl.name, pl.label,
                    COUNT(u.id) AS cnt,
                    COALESCE(SUM(pay.amount), 0) AS revenue
             FROM plans pl
             LEFT JOIN users u ON u.plan_id = pl.id
             LEFT JOIN payments pay ON pay.user_id = u.id AND pay.status = 'confirmed'
             GROUP BY pl.id ORDER BY pl.sort_order"
        );
    }
}
