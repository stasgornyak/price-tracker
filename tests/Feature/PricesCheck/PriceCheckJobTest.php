<?php

use App\Jobs\PriceCheck;
use App\Models\Subscription;
use App\Services\Parsers\ParserFactoryInterface;
use App\Services\Subscription\CheckPriceAction;
use Mockery\MockInterface;

it('calls CheckPriceAction service when job is handled', function () {
    $subscription = Subscription::factory()->create();

    $this->mock(ParserFactoryInterface::class, function (MockInterface $mock) {
        // No expectations needed, just need an instance
    });

    $this->mock(CheckPriceAction::class, function (MockInterface $mock) use ($subscription) {
        $mock->shouldReceive('handle')->once()->with($subscription, Mockery::type(ParserFactoryInterface::class));
    });

    $job = new PriceCheck($subscription);

    app()->call([$job, 'handle']);
});
