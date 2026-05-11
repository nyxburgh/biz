<?php
class PaymentModel extends Model
{
    protected string $table = 'payments';

    public function getAllWithRelations(array $f = [], int $page = 1, int $per = 20): array
    {
        $w = []; $p = [];
        if (!empty($f['search'])) {
            $w[] = "(u.name LIKE ? OR u.phone LIKE ? OR pay.reference LIKE ?)";
            $s = "%{$f['search']}%";
            array_push($p, $s, $s, $s);
        }
        if (!empty($f['status'])) { $w[] = "pay.status = ?"; $p[] = $f['status']; }
        if (!empty($f['_city_scope'])) { $w[] = "u.city_id = ?"; $p[] = $f['_city_scope']; }
        if (!empty($f['plan']))   { $w[] = "pl.name = ?";    $p[] = $f['plan']; }
        if (!empty($f['from']))   { $w[] = "DATE(pay.created_at) >= ?"; $p[] = $f['from']; }
        if (!empty($f['to']))     { $w[] = "DATE(pay.created_at) <= ?"; $p[] = $f['to']; }

        $where = $w ? 'WHERE ' . implode(' AND ', $w) : '';
        $sql = "SELECT pay.*, u.name AS user_name, u.email AS user_email, u.phone AS user_phone,
                       pl.name AS plan_name, pl.label AS plan_label
                FROM payments pay
                LEFT JOIN users u  ON pay.user_id = u.id
                LEFT JOIN plans pl ON pay.plan_id = pl.id
                $where
                ORDER BY pay.created_at DESC";

        return Database::paginate($sql, $p, $page, $per);
    }
}
