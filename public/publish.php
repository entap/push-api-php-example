<?php

use Entap\WebPush\Repositories\FileSubscriptionRepository;
use Entap\WebPush\Services\SendNotificationService;
use Minishlink\WebPush\WebPush;

require_once __DIR__ . '/../vendor/autoload.php';

$auth = [
    'VAPID' => [
        'subject' => 'https://github.com/Minishlink/web-push-php-example/',
        'publicKey' => file_get_contents(__DIR__ . '/../keys/public_key.txt'), // don't forget that your public key also lives in app.js
        'privateKey' => file_get_contents(__DIR__ . '/../keys/private_key.txt'), // in the real world, this would be in a secret file
    ],
];

$notification = new SendNotificationService(
    new WebPush($auth),
    new FileSubscriptionRepository()
);
$notification->sendAll('Hello World');
