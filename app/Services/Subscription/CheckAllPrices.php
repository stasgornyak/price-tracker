<?php

namespace App\Services\Subscription;

use App\Jobs\PriceCheck;
use App\Models\Subscription;

class CheckAllPrices
{
    private const int CHUNK_SIZE = 100;

    public function __invoke(): void
    {
        Subscription::query()->chunkById(self::CHUNK_SIZE, function ($subscriptions) {
            foreach ($subscriptions as $subscription) {
                PriceCheck::dispatch($subscription);
            }
        });
    }
}
