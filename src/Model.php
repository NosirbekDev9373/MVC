<?php
namespace App\Core;

use PDO;

abstract class Model
{
    protected PDO $db;
    protected string $table;

    public function __construct()
    {
        $this->db = (new Connect())->getConnection();
    }

    public function all(): array
    {
        $stmt = $this->db->query("SELECT * FROM {$this->table} ORDER BY id DESC");
        return $stmt->fetchAll();
    }

    public function find(int $id): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE id = :id LIMIT 1");
        $stmt->execute(['id' => $id]);
        $res = $stmt->fetch();
        return $res ?: null;
    }

    public function create(array $data): int
    {
        $cols = array_keys($data);
        $placeholders = array_map(fn($c)=>":$c", $cols);
        $sql = "INSERT INTO {$this->table} (" . implode(',', $cols) . ") VALUES (" . implode(',', $placeholders) . ")";
        $stmt = $this->db->prepare($sql);
        $stmt->execute($data);
        return (int)$this->db->lastInsertId($this->table . '_id_seq'); // Postgres sequence name convention
    }

    public function update(int $id, array $data): bool
    {
        $cols = array_keys($data);
        $assign = implode(',', array_map(fn($c)=>"$c=:$c", $cols));
        $data['id'] = $id;
        $sql = "UPDATE {$this->table} SET {$assign} WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($data);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare("DELETE FROM {$this->table} WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }
}