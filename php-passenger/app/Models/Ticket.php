<?php
class Ticket {
    private PDO $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function create(array $data): array {
        $stmt = $this->db->prepare("
            INSERT INTO passenger_tickets (passenger_id, route_id, origin_stop_id, dest_stop_id, price, status)
            VALUES (:passenger_id, :route_id, :origin_stop_id, :dest_stop_id, :price, 'active')
        ");
        $stmt->execute([
            ':passenger_id'   => $data['passenger_id'],
            ':route_id'       => $data['route_id'],
            ':origin_stop_id' => $data['origin_stop_id'],
            ':dest_stop_id'   => $data['dest_stop_id'],
            ':price'          => $data['price'],
        ]);
        return $this->findById((int)$this->db->lastInsertId());
    }

    public function findById(int $id): ?array {
        $stmt = $this->db->prepare("SELECT * FROM passenger_tickets WHERE id = :id");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch() ?: null;
    }

    public function findByPassengerId(int $passenger_id): array {
        $stmt = $this->db->prepare("SELECT * FROM passenger_tickets WHERE passenger_id = :pid ORDER BY created_at DESC");
        $stmt->execute([':pid' => $passenger_id]);
        return $stmt->fetchAll();
    }
}