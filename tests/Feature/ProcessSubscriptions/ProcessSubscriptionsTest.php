<?php

use App\Jobs\ProcessSubscription;
use App\Models\Subscription;
use App\Services\Subscription\ProcessSubscriptions;
use Illuminate\Support\Facades\Queue;

it('dispatches jobs for each subscription', function () {
    Queue::fake();

    Subscription::factory()->count(10)->create();

    $service = new ProcessSubscriptions;
    $service();

    Queue::assertPushed(ProcessSubscription::class, 10);
});

it('dispatches jobs for each subscription in chunks', function () {
    Queue::fake();

    Subscription::factory()->count(150)->create();

    $service = new ProcessSubscriptions;
    $service();

    Queue::assertPushed(ProcessSubscription::class, 150);
});
