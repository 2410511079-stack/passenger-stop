<?php
class Passenger {
    private PDO $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function create(array $data): array {
        $stmt = $this->db->prepare("
            INSERT INTO passenger_passengers (name, email, phone, card_number, balance, zone_id, password)
            VALUES (:name, :email, :phone, :card_number, :balance, :zone_id, :password)
        ");
        $stmt->execute([
            ':name'        => $data['name'],
            ':email'       => $data['email'],
            ':phone'       => $data['phone'] ?? null,
            ':card_number' => $data['card_number'] ?? null,
            ':balance'     => $data['balance'] ?? 0,
            ':zone_id'     => $data['zone_id'] ?? null,
            ':password'    => password_hash($data['password'], PASSWORD_BCRYPT),
        ]);
        return $this->findById((int)$this->db->lastInsertId());
    }

    public function findById(int $id): ?array {
        $stmt = $this->db->prepare("SELECT id, name, email, phone, card_number, balance, zone_id, role, created_at FROM passenger_passengers WHERE id = :id");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch() ?: null;
    }

    public function findByEmail(string $email): ?array {
        $stmt = $this->db->prepare("SELECT * FROM passenger_passengers WHERE email = :email");
        $stmt->execute([':email' => $email]);
        return $stmt->fetch() ?: null;
    }
}