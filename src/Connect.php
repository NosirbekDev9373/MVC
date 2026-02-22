<?php

namespace App\Core;

use PDO;
use PDOException;

class Connect
{
    private $connection;

    public function __construct()
    {
        // .env dan ma'lumotlarni o'qiymiz
        $host = $_ENV['DB_HOST'];
        $port = $_ENV['DB_PORT'];
        $db   = $_ENV['DB_DATABASE'];
        $user = $_ENV['DB_USERNAME'];
        $pass = $_ENV['DB_PASSWORD'];

        // DSN (Data Source Name) yaratish
        $dsn = "pgsql:host={$host};dbname={$db};port={$port};";

        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];

        try {
            $this->connection = new PDO($dsn, $user, $pass, $options);
        } catch (PDOException $e) {
            die("PostgreSQL ga ulanishda xatolik: " . $e->getMessage());
        }
    }

    public function getConnection()
    {
        return $this->connection;
    }
}