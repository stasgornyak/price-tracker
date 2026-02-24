<?php

use App\Models\User;

it('displays the create form for authenticated user', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->get(route('subscriptions.create'));

    $response->assertStatus(200);
    $response->assertSee('name="name"', false);
    $response->assertSee('name="url"', false);
    $response->assertSee('name="email"', false);
    $response->assertSee('name="notes"', false);
});

it('pre-fills the default email with the user email', function () {
    $user = User::factory()->create(['email' => 'user@example.com']);

    $response = $this->actingAs($user)->get(route('subscriptions.create'));

    $response->assertStatus(200);
    $response->assertViewHas('defaultEmail', 'user@example.com');
    $response->assertSee('value="user@example.com"', false);
});

it('redirects to login for guest', function () {
    $response = $this->get(route('subscriptions.create'));

    $response->assertRedirect(route('login'));
});
