<?php
class RabbitMQPublisher {
    public static function publish(string $queue, array $payload): void {
        // Dummy: log ke file dulu, nanti ganti ke RabbitMQ asli
        $log = date('Y-m-d H:i:s') . " | Queue: $queue | " . json_encode($payload) . "\n";
        file_put_contents(__DIR__ . '/../../rabbitmq.log', $log, FILE_APPEND);
    }
}