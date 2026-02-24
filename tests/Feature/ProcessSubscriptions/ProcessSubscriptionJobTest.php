<?php

use App\Jobs\ProcessSubscription;
use App\Models\Subscription;
use App\Services\Parsers\ParserFactoryInterface;
use App\Services\Subscription\ProcessSubscription as ProcessSubscriptionAction;
use Mockery\MockInterface;

it('calls ProcessSubscription service when job is handled', function () {
    $subscription = Subscription::factory()->create();

    $this->mock(ParserFactoryInterface::class, function (MockInterface $mock) {
        // No expectations needed, just need an instance
    });

    $this->mock(ProcessSubscriptionAction::class, function (MockInterface $mock) use ($subscription) {
        $mock->shouldReceive('handle')->once()->with($subscription, Mockery::type(ParserFactoryInterface::class));
    });

    $job = new ProcessSubscription($subscription);

    app()->call([$job, 'handle']);
});
