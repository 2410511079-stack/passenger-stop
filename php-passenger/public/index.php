<?php
$env = parse_ini_file(__DIR__ . '/../.env');
foreach ($env as $key => $val) putenv("$key=$val");

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PATCH, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, X-Passenger-Id');
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') { http_response_code(200); exit; }

spl_autoload_register(function($class) {
    $dirs = ['Core', 'Models', 'Controllers', 'Services', 'Validators'];
    foreach ($dirs as $dir) {
        $file = __DIR__ . "/../app/$dir/$class.php";
        if (file_exists($file)) { require_once $file; return; }
    }
});

$router = new Router();
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$method = $_SERVER['REQUEST_METHOD'];

// Routes (sama seperti sebelumnya)
$router->add('POST',  '/api/passengers',             fn() => (new PassengerController())->store());
$router->add('GET',   '/api/passengers/:id',         fn($p) => (new PassengerController())->show($p));
$router->add('POST',  '/api/tickets',                fn() => (new TicketController())->store());
$router->add('GET',   '/api/tickets',                fn() => (new TicketController())->index());
$router->add('GET',   '/api/notifications',          fn() => (new NotifController())->index());
$router->add('PATCH', '/api/notifications/:id/read', fn($p) => (new NotifController())->markRead($p));
$router->add('GET',   '/health', function() {
    try {
        Database::getInstance()->query('SELECT 1');
        echo json_encode(['status' => 'success', 'code' => 200, 'data' => ['db' => 'connected'], 'message' => 'OK', 'timestamp' => date('c'), 'service' => 'passenger-service']);
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['status' => 'error', 'code' => 500, 'message' => 'DB down']);
    }
});

$router->dispatch($method, $uri);