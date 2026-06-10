<?php
class PassengerController {
    private Passenger $model;

    public function __construct() {
        $this->model = new Passenger();
    }

    public function store(): void {
        $data = json_decode(file_get_contents('php://input'), true) ?? [];
        $errors = PassengerValidator::validateCreate($data);

        if ($errors) {
            $this->respond(422, null, implode(', ', $errors));
            return;
        }

        $existing = $this->model->findByEmail($data['email']);
        if ($existing) {
            $this->respond(400, null, 'Email already registered');
            return;
        }

        $passenger = $this->model->create($data);
        $this->respond(201, $passenger, 'Passenger registered successfully');
    }

    public function show(array $params): void {
        $passenger = $this->model->findById((int)$params['id']);
        if (!$passenger) {
            $this->respond(404, null, 'Passenger not found');
            return;
        }
        $this->respond(200, $passenger, 'OK');
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