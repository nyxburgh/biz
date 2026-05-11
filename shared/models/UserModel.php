<?php
class UserModel extends Model
{
    protected string $table = 'users';

    public function getAllWithPlan(array $f = [], int $page = 1, int $per = 20): array
    {
        $w = []; $p = [];
        if (!empty($f['search'])) {
            $w[] = "(u.name LIKE ? OR u.email LIKE ? OR u.phone LIKE ?)";
            $s = "%{$f['search']}%";
            array_push($p, $s, $s, $s);
        }
        if (!empty($f['plan']))   { $w[] = "pl.name = ?";    $p[] = $f['plan']; }
        if (!empty($f['status'])) { $w[] = "u.status = ?";   $p[] = $f['status']; }
        if (!empty($f['city']))   { $w[] = "u.city_id = ?";  $p[] = $f['city']; }

        $where = $w ? 'WHERE ' . implode(' AND ', $w) : '';
        $sql = "SELECT u.*, pl.name AS plan_name, pl.label AS plan_label, c.name AS city_name
                FROM users u
                LEFT JOIN plans pl ON u.plan_id = pl.id
                LEFT JOIN cities c ON u.city_id = c.id
                $where
                ORDER BY u.created_at DESC";

        return Database::paginate($sql, $p, $page, $per);
    }


    public function getPaidUsers(array $f = [], int $page = 1, int $per = 20): array
    {
        $w = ["pl.name != 'free'"]; $p = [];
        if (!empty($f['search'])) { $w[] = "(u.name LIKE ? OR u.email LIKE ? OR u.phone LIKE ?)"; $s = "%{$f['search']}%"; array_push($p,$s,$s,$s); }
        if (!empty($f['plan']))   { $w[] = "pl.name = ?";   $p[] = $f['plan']; }
        if (!empty($f['status'])) { $w[] = "u.status = ?";  $p[] = $f['status']; }
        if (!empty($f['city']))   { $w[] = "u.city_id = ?"; $p[] = $f['city']; }
        // city scope for city_admin
        if (!empty($f['_city_scope'])) { $w[] = "u.city_id = ?"; $p[] = $f['_city_scope']; }
        $where = 'WHERE ' . implode(' AND ', $w);
        $sql = "SELECT u.*, pl.name AS plan_name, pl.label AS plan_label, c.name AS city_name
                FROM users u LEFT JOIN plans pl ON u.plan_id=pl.id LEFT JOIN cities c ON u.city_id=c.id
                $where ORDER BY u.created_at DESC";
        return Database::paginate($sql, $p, $page, $per);
    }

    public function getFreeUsers(array $f = [], int $page = 1, int $per = 20): array
    {
        $w = ["pl.name = 'free'"]; $p = [];
        if (!empty($f['search'])) { $w[] = "(u.name LIKE ? OR u.phone LIKE ?)"; $s = "%{$f['search']}%"; array_push($p,$s,$s); }
        if (!empty($f['status'])) { $w[] = "u.status = ?";  $p[] = $f['status']; }
        if (!empty($f['city']))   { $w[] = "u.city_id = ?"; $p[] = $f['city']; }
        if (!empty($f['_city_scope'])) { $w[] = "u.city_id = ?"; $p[] = $f['_city_scope']; }
        $where = 'WHERE ' . implode(' AND ', $w);
        $sql = "SELECT u.*, pl.name AS plan_name, pl.label AS plan_label, c.name AS city_name
                FROM users u LEFT JOIN plans pl ON u.plan_id=pl.id LEFT JOIN cities c ON u.city_id=c.id
                $where ORDER BY u.created_at DESC";
        return Database::paginate($sql, $p, $page, $per);
    }

    public function getWithPlan(int $id): array|false
    {
        return Database::fetchOne(
            "SELECT u.*, pl.name AS plan_name, pl.label AS plan_label, c.name AS city_name
             FROM users u
             LEFT JOIN plans pl ON u.plan_id = pl.id
             LEFT JOIN cities c ON u.city_id = c.id
             WHERE u.id = ?", [$id]
        );
    }

    public function getStats(): array
    {
        return [
            'total'   => $this->count(),
            'active'  => $this->count("status = 'active'"),
            'pending' => $this->count("status = 'pending'"),
            'by_plan' => Database::fetchAll(
                "SELECT pl.name, pl.label, COUNT(u.id) AS cnt
                 FROM plans pl
                 LEFT JOIN users u ON u.plan_id = pl.id
                 GROUP BY pl.id ORDER BY pl.sort_order"
            ),
        ];
    }
}
