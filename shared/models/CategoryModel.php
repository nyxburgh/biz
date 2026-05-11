<?php
class CategoryModel extends Model
{
    protected string $table = 'categories';

    public function getAllWithCount(array $f = [], int $page = 1, int $per = 20): array
    {
        $w = []; $p = [];
        if (!empty($f['search'])) { $w[] = "c.name LIKE ?";  $p[] = "%{$f['search']}%"; }
        if (!empty($f['status'])) { $w[] = "c.status = ?";   $p[] = $f['status']; }

        $where = $w ? 'WHERE ' . implode(' AND ', $w) : '';
        $sql = "SELECT c.*,
                       COUNT(DISTINCT s.id)  AS sub_count,
                       COUNT(DISTINCT bl.id) AS listing_count
                FROM categories c
                LEFT JOIN subcategories s   ON s.category_id = c.id
                LEFT JOIN business_listings bl ON bl.category_id = c.id
                $where
                GROUP BY c.id
                ORDER BY c.sort_order, c.name";

        return Database::paginate($sql, $p, $page, $per);
    }

    public function getSubcategories(int $catId, array $f = [], int $page = 1, int $per = 20): array
    {
        $w = ["s.category_id = ?"]; $p = [$catId];
        if (!empty($f['search'])) { $w[] = "s.name LIKE ?"; $p[] = "%{$f['search']}%"; }
        if (!empty($f['status'])) { $w[] = "s.status = ?";  $p[] = $f['status']; }

        $sql = "SELECT s.*, c.name AS cat_name
                FROM subcategories s
                JOIN categories c ON s.category_id = c.id
                WHERE " . implode(' AND ', $w) . "
                ORDER BY s.sort_order, s.name";

        return Database::paginate($sql, $p, $page, $per);
    }

    public function allActive(): array
    {
        return $this->all("status = 'active'", [], 'sort_order ASC, name ASC');
    }
}
