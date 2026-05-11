<?php
class ListingModel extends Model
{
    protected string $table = 'business_listings';

    public function getAllWithRelations(array $f = [], int $page = 1, int $per = 20): array
    {
        $w = []; $p = [];
        if (!empty($f['search'])) {
            $w[] = "(bl.business_name LIKE ? OR u.name LIKE ? OR u.phone LIKE ?)";
            $s = "%{$f['search']}%";
            array_push($p, $s, $s, $s);
        }
        if (!empty($f['status']))   { $w[] = "bl.status = ?";      $p[] = $f['status']; }
        if (!empty($f['_city_scope'])) { $w[] = "bl.city_id = ?"; $p[] = $f['_city_scope']; }
        if (!empty($f['plan']))     { $w[] = "bl.plan_level = ?";   $p[] = $f['plan']; }
        if (!empty($f['city']))     { $w[] = "bl.city_id = ?";      $p[] = $f['city']; }
        if (!empty($f['category'])) { $w[] = "bl.category_id = ?";  $p[] = $f['category']; }

        $where = $w ? 'WHERE ' . implode(' AND ', $w) : '';

        // For pending status: only show listings where payment is confirmed OR plan is free
        $paymentFilter = '';
        if (!empty($f['status']) && $f['status'] === 'pending') {
            $paymentFilter = "AND (
                bl.plan_level = 'free'
                OR EXISTS (
                    SELECT 1 FROM payments p
                    WHERE p.user_id = bl.user_id
                      AND p.status = 'confirmed'
                )
            )";
        }

        $sql = "SELECT bl.*, u.name AS user_name, u.email AS user_email, u.phone AS user_phone,
                       c.name AS city_name, cat.name AS category_name
                FROM business_listings bl
                LEFT JOIN users u   ON bl.user_id = u.id
                LEFT JOIN cities c  ON bl.city_id = c.id
                LEFT JOIN categories cat ON bl.category_id = cat.id
                $where
                $paymentFilter
                ORDER BY bl.created_at DESC";

        return Database::paginate($sql, $p, $page, $per);
    }

    public function getFullDetail(int $id): array|false
    {
        return Database::fetchOne(
            "SELECT bl.*, u.name AS uname, u.email AS uemail, u.phone AS uphone,
                    c.name AS city_name, cat.name AS cat_name
             FROM business_listings bl
             LEFT JOIN users u   ON bl.user_id = u.id
             LEFT JOIN cities c  ON bl.city_id = c.id
             LEFT JOIN categories cat ON bl.category_id = cat.id
             WHERE bl.id = ?", [$id]
        );
    }

    public function getStats(): array
    {
        $pendingCount = (int)(Database::fetchOne(
            "SELECT COUNT(*) AS c FROM business_listings bl
             WHERE bl.status = 'pending'
               AND (
                   bl.plan_level = 'free'
                   OR EXISTS (
                       SELECT 1 FROM payments p
                       WHERE p.user_id = bl.user_id AND p.status = 'confirmed'
                   )
               )"
        )['c'] ?? 0);

        return [
            'total'    => $this->count(),
            'approved' => $this->count("status = 'approved'"),
            'pending'  => $pendingCount,
            'rejected' => $this->count("status = 'rejected'"),
        ];
    }
}
