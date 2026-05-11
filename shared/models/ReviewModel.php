<?php
class ReviewModel extends Model
{
    protected string $table = 'listing_reviews';

    public function getAllWithRelations(array $f = [], int $page = 1, int $per = 20): array
    {
        $w = []; $p = [];

        if (!empty($f['search'])) {
            $w[] = "(r.reviewer_name LIKE ? OR r.comment LIKE ? OR bl.business_name LIKE ?)";
            $s = "%{$f['search']}%";
            array_push($p, $s, $s, $s);
        }
        if (!empty($f['status']))  { $w[] = "r.status = ?";     $p[] = $f['status']; }
        if (!empty($f['rating']))  { $w[] = "r.rating = ?";     $p[] = $f['rating']; }
        if (!empty($f['listing'])) { $w[] = "r.listing_id = ?"; $p[] = $f['listing']; }
        if (!empty($f['_city_scope'])) { $w[] = "bl.city_id = ?"; $p[] = $f['_city_scope']; }

        $where = $w ? 'WHERE ' . implode(' AND ', $w) : '';
        $sql = "SELECT r.*,
                       bl.business_name, bl.plan_level,
                       u.name AS user_name
                FROM listing_reviews r
                LEFT JOIN business_listings bl ON r.listing_id = bl.id
                LEFT JOIN users u              ON r.user_id = u.id
                $where
                ORDER BY r.created_at DESC";

        return Database::paginate($sql, $p, $page, $per);
    }

    public function getForListing(int $listingId, bool $approvedOnly = true): array
    {
        $status = $approvedOnly ? "AND r.status = 'approved'" : '';
        return Database::fetchAll(
            "SELECT r.*, u.name AS user_name
             FROM listing_reviews r
             LEFT JOIN users u ON r.user_id = u.id
             WHERE r.listing_id = ? $status
             ORDER BY r.created_at DESC",
            [$listingId]
        );
    }

    public function getAverageRating(int $listingId): float
    {
        $row = Database::fetchOne(
            "SELECT ROUND(AVG(rating), 1) AS avg_r, COUNT(*) AS cnt
             FROM listing_reviews
             WHERE listing_id = ? AND status = 'approved'",
            [$listingId]
        );
        return (float)($row['avg_r'] ?? 0);
    }

    public function getStats(): array
    {
        return [
            'total'    => $this->count(),
            'pending'  => $this->count("status = 'pending'"),
            'approved' => $this->count("status = 'approved'"),
            'rejected' => $this->count("status = 'rejected'"),
        ];
    }
}
