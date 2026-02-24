<?php

use App\Models\Subscription;
use App\Models\User;

it('updates the subscription with valid data', function () {
    $user = User::factory()->create();
    $subscription = Subscription::factory()->create(['user_id' => $user->id]);

    $data = [
        'name' => 'Updated Name',
        'email' => 'updated@example.com',
        'notes' => 'Updated notes',
    ];

    $response = $this->actingAs($user)->put(route('subscriptions.update', $subscription), $data);

    $response->assertRedirect(route('subscriptions.index'));
    $response->assertSessionHas('success', 'Subscription updated.');

    $this->assertDatabaseHas('subscriptions', [
        'id' => $subscription->id,
        'name' => 'Updated Name',
        'email' => 'updated@example.com',
        'notes' => 'Updated notes',
    ]);
});

it('validation errors for missing name on update', function () {
    $user = User::factory()->create();
    $subscription = Subscription::factory()->create(['user_id' => $user->id]);

    $response = $this->actingAs($user)->put(route('subscriptions.update', $subscription), [
        'email' => 'updated@example.com',
    ]);

    $response->assertSessionHasErrors(['name']);
});

it('prevents updating another user subscription', function () {
    $user = User::factory()->create();
    $otherUser = User::factory()->create();
    $subscription = Subscription::factory()->create(['user_id' => $otherUser->id]);

    $response = $this->actingAs($user)->put(route('subscriptions.update', $subscription), [
        'name' => 'Updated Name',
        'email' => 'updated@example.com',
    ]);

    $response->assertRedirect(route('subscriptions.index'));
    $response->assertSessionHas('error', 'Subscription not found.');

    $this->assertDatabaseMissing('subscriptions', [
        'id' => $subscription->id,
        'name' => 'Updated Name',
    ]);
});

it('redirects to login for guest', function () {
    $subscription = Subscription::factory()->create();

    $response = $this->put(route('subscriptions.update', $subscription), []);

    $response->assertRedirect(route('login'));
});
