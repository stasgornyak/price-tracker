<?php

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
