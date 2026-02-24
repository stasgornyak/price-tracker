<?php

use App\Models\Subscription;
use App\Models\User;

it('displays the edit form for the user subscription', function () {
    $user = User::factory()->create();
    $subscription = Subscription::factory()->create(['user_id' => $user->id]);

    $response = $this->actingAs($user)->get(route('subscriptions.edit', $subscription));

    $response->assertStatus(200);
    $response->assertSee('value="'.$subscription->name.'"', false);
    $response->assertSee('value="'.$subscription->email.'"', false);
});

it('does not display the edit form for another user subscription', function () {
    $user = User::factory()->create();
    $otherUser = User::factory()->create();
    $subscription = Subscription::factory()->create(['user_id' => $otherUser->id]);

    $response = $this->actingAs($user)->get(route('subscriptions.edit', $subscription));

    $response->assertStatus(302);
    $response->assertSessionHas('error', 'Subscription not found.');
});

it('redirects to login for guest', function () {
    $subscription = Subscription::factory()->create();

    $response = $this->get(route('subscriptions.edit', $subscription));

    $response->assertRedirect(route('login'));
});
