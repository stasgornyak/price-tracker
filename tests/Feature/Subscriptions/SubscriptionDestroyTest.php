<?php

use App\Models\Subscription;
use App\Models\User;

it('deletes the user subscription', function () {
    $user = User::factory()->create();
    $subscription = Subscription::factory()->create(['user_id' => $user->id]);

    $response = $this->actingAs($user)->delete(route('subscriptions.destroy', $subscription));

    $response->assertRedirect(route('subscriptions.index'));
    $response->assertSessionHas('success', 'Subscription deleted.');

    $this->assertDatabaseMissing('subscriptions', [
        'id' => $subscription->id,
    ]);
});

it('prevents deleting another user subscription', function () {
    $user = User::factory()->create();
    $otherUser = User::factory()->create();
    $subscription = Subscription::factory()->create(['user_id' => $otherUser->id]);

    $response = $this->actingAs($user)->delete(route('subscriptions.destroy', $subscription));

    $response->assertRedirect(route('subscriptions.index'));
    $response->assertSessionHas('error', 'Subscription not found.');

    $this->assertDatabaseHas('subscriptions', [
        'id' => $subscription->id,
    ]);
});

it('redirects to login for guest', function () {
    $subscription = Subscription::factory()->create();

    $response = $this->delete(route('subscriptions.destroy', $subscription));

    $response->assertRedirect(route('login'));
});
