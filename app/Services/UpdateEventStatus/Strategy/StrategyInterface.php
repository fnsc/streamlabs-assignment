<?php

namespace App\Services\UpdateEventStatus\Strategy;

use App\Models\User;

interface StrategyInterface
{
    public function execute(int $id, bool $status, User $user): bool;
}
