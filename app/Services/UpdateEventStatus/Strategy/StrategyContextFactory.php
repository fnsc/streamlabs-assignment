<?php

namespace App\Services\UpdateEventStatus\Strategy;

class StrategyContextFactory
{
    private const DONATION = 'donation';

    private const SUBSCRIBER = 'subscriber';

    private const MERCH_SALE = 'merch_sale';

    public function make(string $type): StrategyContext
    {
        if (self::DONATION === $type) {
            $strategy = app(DonationStrategy::class);

            return new StrategyContext($strategy);
        }

        if (self::SUBSCRIBER === $type) {
            $strategy = app(SubscriberStrategy::class);

            return new StrategyContext($strategy);
        }

        $strategy = app(MerchSaleStrategy::class);

        return new StrategyContext($strategy);
    }
}
