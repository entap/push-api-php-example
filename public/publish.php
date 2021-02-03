<?php

use Entap\WebPush\Repositories\FileSubscriptionRepository;
use Entap\WebPush\Services\SendNotificationService;
use Minishlink\WebPush\WebPush;

require_once __DIR__ . '/vendor/autoload.php';

$notification = new SendNotificationService(
    new WebPush(),
    new FileSubscriptionRepository()
);
$notification->sendAll('Hello World');
