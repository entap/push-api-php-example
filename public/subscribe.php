<?php

use Entap\WebPush\Repositories\FileSubscriptionRepository;

require_once __DIR__ . '/../vendor/autoload.php';

// application/json
$jsonData = json_decode(file_get_contents('php://input'), true);

$subscriptions = new FileSubscriptionRepository();
$subscriptions->save($jsonData);

echo json_encode([
    'status' => 'ok',
]);
