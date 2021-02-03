<?php
namespace Entap\WebPush\Repositories;

interface SubscriptionRepository
{
    public function save($subscription): void;
    public function all(): array;
}
