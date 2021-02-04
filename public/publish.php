<?php

use Entap\WebPush\Repositories\FileSubscriptionRepository;
use Entap\WebPush\Services\SendNotificationService;
use Minishlink\WebPush\WebPush;

require_once __DIR__ . '/../vendor/autoload.php';

$publicKeyPath = __DIR__ . '/../keys/public_key.txt';
$privateKeyPath = __DIR__ . '/../keys/private_key.txt';

// application/json
$subscription = json_decode(file_get_contents('php://input'), true);

$auth = [
    'VAPID' => [
        'subject' => 'https://github.com/entap/push-api-php-example',
        'publicKey' => file_get_contents($publicKeyPath), // don't forget that your public key also lives in app.js
        'privateKey' => file_get_contents($privateKeyPath), // in the real world, this would be in a secret file
    ],
];

$notification = new SendNotificationService(
    new WebPush($auth),
    new FileSubscriptionRepository()
);

$notification->sendAll('こんにちは 👋');

// TODO subscriptionをuser_idなどと紐づけて取ってこれるようにするといい
// $notification->send(
//     $subscription['endpoint'],
//     $subscription['keys']['p256dh'],
//     $subscription['keys']['auth'],
//     'こんにちは 👋'
// );

return json_encode(['status' => 'ok']);
