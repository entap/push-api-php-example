<?php
namespace Entap\WebPush\Services;

use Entap\WebPush\Repositories\SubscriptionRepository;
use Minishlink\WebPush\WebPush;
use Minishlink\WebPush\Subscription;

class SendNotificationService
{
    protected $publisher;
    protected $subscriptions;

    public function __construct(
        WebPush $publisher,
        SubscriptionRepository $subscriptions
    ) {
        $this->publisher = $publisher;
        $this->subscriptions = $subscriptions;
    }

    public function send(
        $endpoint,
        $publicKey,
        $authToken,
        string $payload = null
    ): void {
        $subscription = Subscription::create(
            compact('endpoint', 'publicKey', 'authToken')
        );
        $this->publisher->sendOneNotification($subscription, $payload);
    }

    public function sendAll(string $payload = null): void
    {
        foreach ($this->subscriptions->all() as $subscription) {
            $this->queue(
                $subscription['endpoint'],
                $subscription['keys']['p256dh'],
                $subscription['keys']['auth'],
                $payload
            );
        }
        $this->flushQueue();
    }

    protected function queue(
        $endpoint,
        $publicKey,
        $authToken,
        string $payload = null
    ): void {
        $subscription = Subscription::create(
            compact('endpoint', 'publicKey', 'authToken')
        );
        $this->publisher->queueNotification($subscription, $payload);
    }

    protected function flushQueue(): void
    {
        $this->publisher->flush();

        // foreach ($this->publisher->flush() as $report) {
        //   $endpoint = $report
        //     ->getRequest()
        //     ->getUri()
        //     ->__toString();

        //   if ($report->isSuccess()) {
        //     echo "[v] Message sent successfully for subscription {$endpoint}.";
        //   } else {
        //     echo "[x] Message failed to sent for subscription {$endpoint}: {$report->getReason()}";
        //   }
        // }
    }
}
