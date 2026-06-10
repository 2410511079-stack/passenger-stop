<?php
class Notification {
    private PDO $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function findByPassengerId(int $passenger_id): array {
        $stmt = $this->db->prepare("SELECT * FROM passenger_notifications WHERE passenger_id = :pid ORDER BY created_at DESC");
        $stmt->execute([':pid' => $passenger_id]);
        return $stmt->fetchAll();
    }

    public function markAsRead(int $id): bool {
        $stmt = $this->db->prepare("UPDATE passenger_notifications SET is_read = 1 WHERE id = :id");
        $stmt->execute([':id' => $id]);
        return $stmt->rowCount() > 0;
    }
}