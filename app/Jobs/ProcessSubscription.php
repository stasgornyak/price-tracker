<?php

namespace App\Jobs;

use App\Models\Subscription;
use App\Services\Parsers\ParserFactoryInterface;
use App\Services\Subscription\ProcessSubscription as ProcessSubscriptionAction;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Throwable;

class ProcessSubscription implements ShouldBeUnique, ShouldQueue
{
    use Queueable;

    public int $tries = 3;

    public array $backoff = [10, 30, 60];

    public function __construct(
        public Subscription $subscription
    ) {}

    public function uniqueId(): string
    {
        return $this->subscription->id;
    }

    /**
     * @throws Throwable
     */
    public function handle(ParserFactoryInterface $parserFactory, ProcessSubscriptionAction $processSubscription): void
    {
        try {
            $processSubscription->handle($this->subscription, $parserFactory);
        } catch (Throwable $e) {
            report($e);
        }
    }
}
