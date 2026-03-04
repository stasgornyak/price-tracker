<?php

namespace App\Services\Subscription;

use App\Models\PriceHistory;
use App\Models\Subscription;

class SavePriceHistoryAction
{
    public function handle(Subscription $subscription, float $price): void
    {
        PriceHistory::create([
            'subscription_id' => $subscription->id,
            'price' => $price,
            'recorded_at' => now(),
        ]);
    }
}
