<?php

namespace App\Mail;

use App\Models\Subscription;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PriceChanged extends Mailable implements ShouldQueue
{
    use SerializesModels;

    public function __construct(public Subscription $subscription, public float $oldPrice) {}

    public function envelope(): Envelope
    {
        $this->subscription->load('user');

        return new Envelope(
            from: new Address(config('mail.from.address'), config('mail.from.name')),
            to: [new Address($this->subscription->email, $this->subscription->user->name)],
            subject: __('Price Changed'),
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'email.price-changed',
            with: [
                'user_name' => $this->subscription->user->name,
                'item_name' => $this->subscription->name,
                'old_price' => $this->oldPrice,
                'new_price' => $this->subscription->current_price,
                'url' => $this->subscription->url,
            ]
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
