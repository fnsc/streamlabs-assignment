<?php

namespace App\Services\UpdateEventStatus;

use App\Services\UpdateEventStatus\Strategy\StrategyContextFactory;

class UpdateEventStatusService
{
    public function __construct(private readonly StrategyContextFactory $contextFactory)
    {
    }

    public function handle(UpdateEventStatusInput $input): bool
    {
        $strategyContext = $this->contextFactory->make($input->getType());

        return $strategyContext->process($input->getId(), $input->getStatus(), $input->getUser());
    }
}
