<?php

use App\Models\Subscription;
use App\Models\User;

it('creates a new subscription with valid data', function () {
    $user = User::factory()->create();
    $sourceBaseUrl = config('subscriptions.parsers.olx_ua.base_url').'/';

    $data = [
        'name' => 'My Subscription',
        'url' => $sourceBaseUrl.'uk/d/obyavlenie/some-item-ID.html',
        'email' => 'test@example.com',
        'notes' => 'Some notes',
    ];

    $response = $this->actingAs($user)->post(route('subscriptions.store'), $data);

    $response->assertRedirect(route('subscriptions.index'));
    $response->assertSessionHas('success', 'Subscription created.');

    $this->assertDatabaseHas('subscriptions', [
        'user_id' => $user->id,
        'name' => 'My Subscription',
        'email' => 'test@example.com',
        'notes' => 'Some notes',
    ]);
});

it('validation errors for missing name', function () {
    $user = User::factory()->create();
    $sourceBaseUrl = config('subscriptions.parsers.olx_ua.base_url').'/';

    $response = $this->actingAs($user)->post(route('subscriptions.store'), [
        'url' => $sourceBaseUrl.'uk/d/obyavlenie/some-item-ID.html',
        'email' => 'test@example.com',
    ]);

    $response->assertSessionHasErrors(['name']);
});

it('validation errors for invalid url', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->post(route('subscriptions.store'), [
        'name' => 'My Subscription',
        'url' => 'https://invalid-url.com/some-item',
        'email' => 'test@example.com',
    ]);

    $response->assertSessionHasErrors(['url']);
});

it('prevents duplicate subscriptions for same user', function () {
    $user = User::factory()->create();
    $sourceBaseUrl = config('subscriptions.parsers.olx_ua.base_url').'/';
    $url = $sourceBaseUrl.'uk/d/obyavlenie/some-item-ID.html';

    Subscription::factory()->create([
        'user_id' => $user->id,
        'url' => $url,
    ]);

    $response = $this->actingAs($user)->post(route('subscriptions.store'), [
        'name' => 'Duplicate',
        'url' => $url,
        'email' => 'test@example.com',
    ]);

    $response->assertStatus(302);
    $response->assertSessionHas('error', 'Subscription already exists.');
    $this->assertEquals(1, Subscription::where('user_id', $user->id)->where('url', $url)->count());
});

it('allows same url for different users', function () {
    $user1 = User::factory()->create();
    $user2 = User::factory()->create();
    $sourceBaseUrl = config('subscriptions.parsers.olx_ua.base_url').'/';
    $url = $sourceBaseUrl.'uk/d/obyavlenie/some-item-ID.html';

    Subscription::factory()->create([
        'user_id' => $user1->id,
        'url' => $url,
    ]);

    $response = $this->actingAs($user2)->post(route('subscriptions.store'), [
        'name' => 'New User Sub',
        'url' => $url,
        'email' => 'user2@example.com',
    ]);

    $response->assertRedirect(route('subscriptions.index'));
    $this->assertEquals(2, Subscription::where('url', $url)->count());
});

it('redirects to login for guest', function () {
    $response = $this->post(route('subscriptions.store'), []);

    $response->assertRedirect(route('login'));
});
