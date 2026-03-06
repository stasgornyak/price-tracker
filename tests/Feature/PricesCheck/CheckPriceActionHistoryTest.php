<?php

use App\Models\PriceHistory;
use App\Models\Subscription;
use App\Services\Parsers\ParserFactoryInterface;
use App\Services\Parsers\ParserInterface;
use App\Services\Subscription\CheckPriceAction;
use Mockery\MockInterface;

function makeParserFactory(float $price): ParserFactoryInterface
{
    $parser = Mockery::mock(ParserInterface::class);
    $parser->shouldReceive('parsePrice')->andReturn($price);

    $factory = Mockery::mock(ParserFactoryInterface::class);
    $factory->shouldReceive('createParser')->andReturn($parser);

    return $factory;
}

it('records history on first price check', function () {
    $subscription = Subscription::factory()->create(['current_price' => null]);

    (new CheckPriceAction)->handle($subscription, makeParserFactory(150.00));

    expect(PriceHistory::where('subscription_id', $subscription->id)->count())->toBe(1);
    expect(PriceHistory::where('subscription_id', $subscription->id)->first()->price)->toBe(150.0);
});

it('records history when price changes', function () {
    $subscription = Subscription::factory()->create(['current_price' => 100.00]);

    (new CheckPriceAction)->handle($subscription, makeParserFactory(200.00));

    expect(PriceHistory::where('subscription_id', $subscription->id)->count())->toBe(1);
    expect(PriceHistory::where('subscription_id', $subscription->id)->first()->price)->toBe(200.0);
});

it('does not record history when price is unchanged', function () {
    $subscription = Subscription::factory()->create(['current_price' => 100.00]);

    (new CheckPriceAction)->handle($subscription, makeParserFactory(100.00));

    expect(PriceHistory::where('subscription_id', $subscription->id)->count())->toBe(0);
});

it('records two history entries across two sequential price changes', function () {
    $subscription = Subscription::factory()->create(['current_price' => 100.00]);

    (new CheckPriceAction)->handle($subscription, makeParserFactory(200.00));
    $subscription->refresh();
    (new CheckPriceAction)->handle($subscription, makeParserFactory(300.00));

    expect(PriceHistory::where('subscription_id', $subscription->id)->count())->toBe(2);
});
