<?php

use App\Jobs\PriceCheck;
use App\Models\Subscription;
use App\Services\Subscription\CheckAllPrices;
use Illuminate\Support\Facades\Queue;

it('dispatches jobs for each subscription', function () {
    Queue::fake();

    Subscription::factory()->count(10)->create();

    $service = new CheckAllPrices;
    $service();

    Queue::assertPushed(PriceCheck::class, 10);
});

it('dispatches jobs for each subscription in chunks', function () {
    Queue::fake();

    Subscription::factory()->count(150)->create();

    $service = new CheckAllPrices;
    $service();

    Queue::assertPushed(PriceCheck::class, 150);
});
