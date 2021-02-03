<?php
namespace Entap\WebPush\Repositories;

class FileSubscriptionRepository implements SubscriptionRepository
{
    public function save($subscription): void
    {
        $fileName = '/tmp/posket-mall/test-push.json';
        $jsonData = json_decode(file_get_contents($fileName), true);
        if ($jsonData['subscribers']) {
            $jsonData['subscribers'] = [];
        }
        $jsonData['subscribers'][] = [
            'endpoint' => $subscription['endpoint'],
            'publicKey' => $subscription['publicKey'],
            'authSecret' => $subscription['authSecret'],
        ];
        file_put_contents($fileName, json_encode($jsonData));
    }

    public function all(): array
    {
        $fileName = '/tmp/posket-mall/test-push.json';
        $contents = file_get_contents($fileName);
        if (empty($contents)) {
            return [];
        }
        $jsonData = json_decode($contents, true);
        if ($jsonData['subscribers']) {
            $jsonData['subscribers'] = [];
        }
        return $jsonData['subscribers'];
    }
}
