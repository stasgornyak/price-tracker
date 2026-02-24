<?php

use App\Http\Controllers\SubscriptionController;
use App\Models\Subscription;
use App\Models\User;

it('displays the subscriptions list for the authenticated user', function () {
    $user = User::factory()->create();
    $subscriptions = Subscription::factory()->count(3)->create(['user_id' => $user->id]);

    $response = $this->actingAs($user)->get(route('subscriptions.index'));

    $response->assertStatus(200);
    foreach ($subscriptions as $subscription) {
        $response->assertSee($subscription->name);
    }
});

it('does not display subscriptions of other users', function () {
    $user = User::factory()->create();
    $otherUser = User::factory()->create();
    $subscription = Subscription::factory()->create(['user_id' => $otherUser->id]);

    $response = $this->actingAs($user)->get(route('subscriptions.index'));

    $response->assertStatus(200);
    $response->assertDontSee($subscription->name);
});

it('paginates the subscriptions list', function () {
    $user = User::factory()->create();
    Subscription::factory()->count(SubscriptionController::PAGE_SIZE + 1)->create(['user_id' => $user->id]);

    $response = $this->actingAs($user)->get(route('subscriptions.index'));

    $response->assertStatus(200);
    $response->assertViewHas('subscriptions', function ($subscriptions) {
        return $subscriptions->count() === SubscriptionController::PAGE_SIZE;
    });
});

it('redirects to login for guest', function () {
    $response = $this->get(route('subscriptions.index'));

    $response->assertRedirect(route('login'));
});
