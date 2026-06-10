<?php
class TicketValidator {
    public static function validateCreate(array $data): array {
        $errors = [];
        if (empty($data['route_id']))       $errors[] = 'route_id is required';
        if (empty($data['origin_stop_id'])) $errors[] = 'origin_stop_id is required';
        if (empty($data['dest_stop_id']))   $errors[] = 'dest_stop_id is required';
        if (empty($data['price']))          $errors[] = 'price is required';
        return $errors;
    }
}