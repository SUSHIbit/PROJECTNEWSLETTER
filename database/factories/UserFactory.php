<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'username' => fake()->optional(0.7)->userName(), // 70% chance of having username
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            'account_type' => fake()->randomElement(['personal', 'organization']),
            'bio' => fake()->optional(0.6)->paragraph(2), // 60% chance of having bio
            'location' => fake()->optional(0.5)->city() . ', ' . fake()->optional(0.5)->country(),
            'website' => fake()->optional(0.3)->url(),
            'last_active_at' => fake()->optional(0.8)->dateTimeBetween('-1 month', 'now'),
            'remember_token' => Str::random(10),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }

    /**
     * Indicate that the user is an organization.
     */
    public function organization(): static
    {
        return $this->state(fn (array $attributes) => [
            'account_type' => 'organization',
        ]);
    }

    /**
     * Indicate that the user is a personal account.
     */
    public function personal(): static
    {
        return $this->state(fn (array $attributes) => [
            'account_type' => 'personal',
        ]);
    }
}