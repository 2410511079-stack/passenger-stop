<?php
class TicketController {
    private Ticket $model;

    public function __construct() {
        $this->model = new Ticket();
    }

    public function store(): void {
        // Simulasi passenger_id dari header (nanti dari JWT)
        $passengerId = (int)($_SERVER['HTTP_X_PASSENGER_ID'] ?? 1);
        $data = json_decode(file_get_contents('php://input'), true) ?? [];
        $errors = TicketValidator::validateCreate($data);

        if ($errors) {
            $this->respond(422, null, implode(', ', $errors));
            return;
        }

        $data['passenger_id'] = $passengerId;
        $ticket = $this->model->create($data);

        RabbitMQPublisher::publish('ticket.purchased', [
            'ticket_id'    => $ticket['id'],
            'passenger_id' => $passengerId,
            'route_id'     => $ticket['route_id'],
            'timestamp'    => date('c')
        ]);

        $this->respond(201, $ticket, 'Ticket purchased successfully');
    }

    public function index(): void {
        $passengerId = (int)($_SERVER['HTTP_X_PASSENGER_ID'] ?? 1);
        $tickets = $this->model->findByPassengerId($passengerId);
        $this->respond(200, $tickets, 'OK');
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