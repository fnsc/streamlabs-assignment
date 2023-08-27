<?php

namespace App\Services\UpdateEventStatus\Strategy;

use App\Models\User;
use App\Repositories\SubscriberRepository;

class SubscriberStrategy implements StrategyInterface
{

    public function __construct(private readonly SubscriberRepository $repository)
    {
    }

    public function execute(int $id, bool $status, User $user): bool
    {
        return $this->repository->updateStatus($id, $status, $user);
    }
}
