<?php
class NotifController {
    private Notification $model;

    public function __construct() {
        $this->model = new Notification();
    }

    public function index(): void {
        $passengerId = (int)($_SERVER['HTTP_X_PASSENGER_ID'] ?? 1);
        $notifs = $this->model->findByPassengerId($passengerId);
        $this->respond(200, $notifs, 'OK');
    }

    public function markRead(array $params): void {
        $updated = $this->model->markAsRead((int)$params['id']);
        if (!$updated) {
            $this->respond(404, null, 'Notification not found');
            return;
        }
        $this->respond(200, null, 'Notification marked as read');
    }

    private function respond(int $code, $data, string $message): void {
        http_response_code($code);
        echo json_encode([
            'status'    => $code < 400 ? 'success' : 'error',
            'code'      => $code,
            'data'      => $data,
            'message'   => $message,
            'timestamp' => date('c'),
            'service'   => 'passenger-service'
        ]);
    }
}