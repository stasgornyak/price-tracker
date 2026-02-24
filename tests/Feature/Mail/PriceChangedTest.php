<?php

use App\Mail\PriceChanged;
use App\Models\Subscription;
use App\Models\User;

test('price changed email contains correct data', function () {
    $user = User::factory()->create(['name' => 'John Doe']);
    $subscription = Subscription::factory()->create([
        'user_id' => $user->id,
        'name' => 'Awesome Product',
        'current_price' => 150.00,
        'url' => 'https://example.com/product',
    ]);

    $oldPrice = 100.00;

    $mailable = new PriceChanged($subscription, $oldPrice);

    $mailable->assertSeeInHtml('Dear John Doe!');
    $mailable->assertSeeInHtml('The price of the');
    $mailable->assertSeeInHtml('Awesome Product');
    $mailable->assertSeeInHtml('you were interested in has changed from');
    $mailable->assertSeeInHtml('100.00 UAH');
    $mailable->assertSeeInHtml('to');
    $mailable->assertSeeInHtml('150.00 UAH');
    $mailable->assertSeeInHtml('<a href="https://example.com/product">https://example.com/product</a>', false);
});
