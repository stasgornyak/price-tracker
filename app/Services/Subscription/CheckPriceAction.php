<?php

namespace App\Services\Subscription;

use App\Events\PriceChanged;
use App\Models\Subscription;
use App\Services\Parsers\ParserFactoryInterface;
use Illuminate\Support\Facades\DB;
use Throwable;

class CheckPriceAction
{
    public function __construct(
        private ?Subscription $subscription = null,
        private ?ParserFactoryInterface $parserFactory = null
    ) {}

    /**
     * @throws Throwable
     */
    public function handle(Subscription $subscription, ParserFactoryInterface $parserFactory): void
    {
        $this->subscription = $subscription;
        $this->parserFactory = $parserFactory;

        $subscriptionWithCheckedPrice = $this->findSubscriptionWithCheckedPrice();

        if ($subscriptionWithCheckedPrice) {
            $newPrice = $subscriptionWithCheckedPrice->current_price;
            $priceCheckedAt = $subscriptionWithCheckedPrice->price_checked_at;
        } else {
            $parser = $this->parserFactory->createParser($this->subscription->url);
            $newPrice = $parser->parsePrice($this->subscription->url);
            $priceCheckedAt = now();
        }

        if (empty($newPrice)) {
            return;
        }

        $oldPrice = $this->subscription->current_price;

        if ($oldPrice === $newPrice) {
            $this->subscription->update([
                'price_checked_at' => $priceCheckedAt,
            ]);

            return;
        }

        if (empty($oldPrice)) {
            $this->subscription->update([
                'current_price' => $newPrice,
                'price_checked_at' => $priceCheckedAt,
            ]);

            return;
        }

        DB::transaction(function () use ($oldPrice, $newPrice, $priceCheckedAt) {
            $this->subscription->update([
                'current_price' => $newPrice,
                'price_checked_at' => $priceCheckedAt,
            ]);

            PriceChanged::dispatch($this->subscription, $oldPrice);
        });
    }

    /**
     * @deprecated use handle instead
     *
     * @throws Throwable
     */
    public function __invoke(): void
    {
        if ($this->subscription === null || $this->parserFactory === null) {
            throw new \RuntimeException('Subscription and ParserFactory must be provided to use __invoke');
        }

        $this->handle($this->subscription, $this->parserFactory);
    }

    private function findSubscriptionWithCheckedPrice(): ?Subscription
    {
        $checkIntervalInMinutes = config('subscriptions.check_interval_in_minutes');

        return Subscription::query()
            ->where('user_id', '<>', $this->subscription->user_id)
            ->where('url', $this->subscription->url)
            ->whereNotNull('current_price')
            ->whereBetween('price_checked_at', [now()->subMinutes($checkIntervalInMinutes / 2), now()])
            ->latest('price_checked_at')
            ->first();
    }
}
