<?php

namespace App\Listeners;

use App\Events\PriceChanged;
use App\Mail\PriceChanged as PriceChangedMail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;

class SendPriceChangedNotification implements ShouldQueue
{
    public function handle(PriceChanged $event): void
    {
        Mail::send(new PriceChangedMail($event->subscription, $event->oldPrice));
    }
}
