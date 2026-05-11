<?php
abstract class Model
{
    protected string $table;
    protected string $primaryKey = 'id';

    public function find(int $id): array|false
    {
        return Database::fetchOne(
            "SELECT * FROM `{$this->table}` WHERE `{$this->primaryKey}` = ?",
            [$id]
        );
    }

    public function all(string $where = '', array $params = [], string $order = 'id DESC'): array
    {
        $sql = "SELECT * FROM `{$this->table}`";
        if ($where) {
            $sql .= " WHERE $where";
        }
        return Database::fetchAll("$sql ORDER BY $order", $params);
    }

    public function create(array $data): int
    {
        $cols = implode(', ', array_map(fn($k) => "`$k`", array_keys($data)));
        $plc  = implode(', ', array_fill(0, count($data), '?'));
        Database::query(
            "INSERT INTO `{$this->table}` ($cols) VALUES ($plc)",
            array_values($data)
        );
        return (int) Database::lastInsertId();
    }

    public function update(int $id, array $data): bool
    {
        $set = implode(', ', array_map(fn($k) => "`$k` = ?", array_keys($data)));
        return Database::execute(
            "UPDATE `{$this->table}` SET $set WHERE `{$this->primaryKey}` = ?",
            [...array_values($data), $id]
        );
    }

    public function delete(int $id): bool
    {
        return Database::execute(
            "DELETE FROM `{$this->table}` WHERE `{$this->primaryKey}` = ?",
            [$id]
        );
    }

    public function count(string $where = '', array $params = []): int
    {
        $sql = "SELECT COUNT(*) AS c FROM `{$this->table}`";
        if ($where) {
            $sql .= " WHERE $where";
        }
        return (int)(Database::fetchOne($sql, $params)['c'] ?? 0);
    }
}
