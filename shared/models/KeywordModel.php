<?php
class KeywordModel extends Model
{
    protected string $table = 'keywords';

    public function getAllWithRelations(array $f = [], int $page = 1, int $per = 20): array
    {
        $w = []; $p = [];
        if (!empty($f['search']))   { $w[] = "k.name LIKE ?";      $p[] = "%{$f['search']}%"; }
        if (!empty($f['category'])) { $w[] = "k.category_id = ?";  $p[] = $f['category']; }
        if (!empty($f['status']))   { $w[] = "k.status = ?";       $p[] = $f['status']; }

        $where = $w ? 'WHERE ' . implode(' AND ', $w) : '';
        $sql = "SELECT k.*, c.name AS cat_name, s.name AS sub_name
                FROM keywords k
                LEFT JOIN categories c   ON k.category_id = c.id
                LEFT JOIN subcategories s ON k.subcategory_id = s.id
                $where
                ORDER BY k.name ASC";

        return Database::paginate($sql, $p, $page, $per);
    }

    public function getSuggestions(array $f = [], int $page = 1, int $per = 20): array
    {
        $w = []; $p = [];
        if (!empty($f['search'])) { $w[] = "ks.keyword LIKE ?"; $p[] = "%{$f['search']}%"; }
        if (!empty($f['status'])) { $w[] = "ks.status = ?";     $p[] = $f['status']; }

        $where = $w ? 'WHERE ' . implode(' AND ', $w) : '';
        $sql = "SELECT ks.*, u.name AS user_name, c.name AS cat_name
                FROM keyword_suggestions ks
                LEFT JOIN users u      ON ks.user_id = u.id
                LEFT JOIN categories c ON ks.category_id = c.id
                $where
                ORDER BY ks.created_at DESC";

        return Database::paginate($sql, $p, $page, $per);
    }
}
