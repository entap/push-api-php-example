<?php
namespace Entap\WebPush\Repositories;

class FileSubscriptionRepository implements SubscriptionRepository
{
    protected $fileName = '/tmp/test-push.json';

    public function __construct()
    {
        if (!file_exists($this->fileName)) {
            file_put_contents($this->fileName, json_encode([]));
        }
    }

    public function save($subscription): void
    {
        $jsonData = [];
        $contents = file_get_contents($this->fileName);
        if ($contents) {
            $jsonData = json_decode($contents, true);
        }
        if (!isset($jsonData['subscribers'])) {
            $jsonData['subscribers'] = [];
        }
        $jsonData['subscribers'][] = $subscription;
        file_put_contents($this->fileName, json_encode($jsonData));
    }

    public function all(): array
    {
        $contents = file_get_contents($this->fileName);
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
