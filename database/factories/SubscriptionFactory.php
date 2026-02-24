<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Subscription>
 */
class SubscriptionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'name' => $this->faker->sentence(3),
            'email' => $this->faker->safeEmail,
            'url' => $this->faker->url,
            'notes' => $this->faker->sentence,
            'current_price' => $this->faker->randomFloat(2, 100, 10000),
            'price_checked_at' => now(),
        ];
    }
}
