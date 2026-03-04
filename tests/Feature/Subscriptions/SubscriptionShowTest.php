<?php

use App\Models\PriceHistory;
use App\Models\Subscription;
use App\Models\User;

it('displays the subscription details', function () {
    $user = User::factory()->create();
    $subscription = Subscription::factory()->create(['user_id' => $user->id]);

    $response = $this->actingAs($user)->get(route('subscriptions.show', $subscription));

    $response->assertStatus(200);
    $response->assertSee($subscription->name);
    $response->assertSee($subscription->url);
    $response->assertSee($subscription->email);
});

it('does not display another user subscription', function () {
    $user = User::factory()->create();
    $otherUser = User::factory()->create();
    $subscription = Subscription::factory()->create(['user_id' => $otherUser->id]);

    $response = $this->actingAs($user)->get(route('subscriptions.show', $subscription));

    $response->assertRedirect(route('subscriptions.index'));
    $response->assertSessionHas('error', 'Subscription not found.');
});

it('redirects to login for guest', function () {
    $subscription = Subscription::factory()->create();

    $response = $this->get(route('subscriptions.show', $subscription));

    $response->assertRedirect(route('login'));
});

it('shows price history table with correct price value', function () {
    $user = User::factory()->create();
    $subscription = Subscription::factory()->create(['user_id' => $user->id]);
    PriceHistory::factory()->create([
        'subscription_id' => $subscription->id,
        'price' => 350.00,
    ]);

    $response = $this->actingAs($user)->get(route('subscriptions.show', $subscription));

    $response->assertStatus(200);
    $response->assertSee('350.00 UAH');
});

it('shows empty state message when no history exists', function () {
    $user = User::factory()->create();
    $subscription = Subscription::factory()->create(['user_id' => $user->id]);

    $response = $this->actingAs($user)->get(route('subscriptions.show', $subscription));

    $response->assertStatus(200);
    $response->assertSee('No price history yet.');
});

it('limits price history display to 20 records', function () {
    $user = User::factory()->create();
    $subscription = Subscription::factory()->create(['user_id' => $user->id]);
    PriceHistory::factory()->count(25)->create(['subscription_id' => $subscription->id]);

    $response = $this->actingAs($user)->get(route('subscriptions.show', $subscription));

    $response->assertStatus(200);
    expect(substr_count($response->getContent(), '<tr>') - 1)->toBeLessThanOrEqual(20); // subtract header row
});
