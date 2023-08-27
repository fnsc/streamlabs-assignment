<?php

namespace App\Services\UpdateEventStatus\Strategy;

use App\Models\User;

class StrategyContext
{
    public function __construct(private readonly StrategyInterface $strategy)
    {
    }

    public function process(int $id, bool $status, User $user): bool
    {
        return $this->strategy->execute($id, $status, $user);
    }
}
