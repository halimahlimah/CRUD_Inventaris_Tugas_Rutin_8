<?php
class Database
{
    private static ?Database $instance = null;
    private PDO $connection;
    private string $host = 'localhost';
    private string $dbName = 'inventaris_db';
    private string $username = 'root';
    private string $password = '';

    private function __construct()
    {
        $dsn = "mysql:host={$this->host};dbname={$this->dbName};charset=utf8mb4";
        $this->connection = new PDO($dsn, $this->username, $this->password, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false
        ]);
    }

    public static function getInstance(): Database
    {
        if (self::$instance === null) {
            self::$instance = new Database();
        }
        return self::$instance;
    }

    public function getConnection(): PDO
    {
        return $this->connection;
    }
}
