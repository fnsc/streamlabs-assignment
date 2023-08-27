<?php

namespace App\Services\UpdateEventStatus\Strategy;

use App\Models\User;
use App\Repositories\MerchSaleRepository;

class MerchSaleStrategy implements StrategyInterface
{
    public function __construct(private readonly MerchSaleRepository $repository)
    {
    }
    public function execute(int $id, bool $status, User $user): bool
    {
        return $this->repository->updateStatus($id, $status, $user);
    }
}
