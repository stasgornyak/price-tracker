<?php

use App\Models\PriceHistory;
use App\Models\Subscription;
use App\Services\Subscription\SavePriceHistoryAction;

it('creates a price history record with correct data', function () {
    $subscription = Subscription::factory()->create();
    $price = 250.50;

    (new SavePriceHistoryAction)->handle($subscription, $price);

    $this->assertDatabaseHas('price_history', [
        'subscription_id' => $subscription->id,
        'price' => $price,
    ]);
});

it('creates multiple records for the same subscription', function () {
    $subscription = Subscription::factory()->create();

    (new SavePriceHistoryAction)->handle($subscription, 100.00);
    (new SavePriceHistoryAction)->handle($subscription, 200.00);

    expect(PriceHistory::where('subscription_id', $subscription->id)->count())->toBe(2);
});
