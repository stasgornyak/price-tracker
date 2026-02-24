<?php

use App\Events\PriceChanged;
use App\Models\Subscription;

it('stores subscription and old price', function () {
    $subscription = Subscription::factory()->make();
    $oldPrice = 100.50;

    $event = new PriceChanged($subscription, $oldPrice);

    expect($event->subscription)->toBe($subscription)
        ->and($event->oldPrice)->toBe($oldPrice);
});
