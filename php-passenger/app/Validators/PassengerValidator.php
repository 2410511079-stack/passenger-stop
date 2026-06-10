<?php
class PassengerValidator {
    public static function validateCreate(array $data): array {
        $errors = [];
        if (empty($data['name']))     $errors[] = 'name is required';
        if (empty($data['email']))    $errors[] = 'email is required';
        elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) $errors[] = 'email is invalid';
        if (empty($data['password'])) $errors[] = 'password is required';
        return $errors;
    }
}