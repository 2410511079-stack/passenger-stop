<?php
class Database {
    private static $instance = null;

    public static function getInstance(): PDO {
        if (self::$instance === null) {
            $host = getenv('DB_HOST') ?: 'localhost';
            $name = getenv('DB_NAME') ?: 'smarttransport_passenger';
            $user = getenv('DB_USER') ?: 'root';
            $pass = getenv('DB_PASS') ?: '';
            $port = getenv('DB_PORT') ?: '3307';

            try {
                self::$instance = new PDO(
                    "mysql:host=$host;port=$port;dbname=$name;charset=utf8mb4",
                    $user,
                    $pass,
                    [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                     PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC]
                );
            } catch (PDOException $e) {
                http_response_code(500);
                echo json_encode(['status' => 'error', 'code' => 500, 'message' => 'DB connection failed: ' . $e->getMessage()]);
                exit;
            }
        }
        return self::$instance;
    }
}