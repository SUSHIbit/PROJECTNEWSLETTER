<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Post>
 */
class PostFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $categories = ['technology', 'politics', 'sports', 'health', 'science', 'business', 'entertainment'];
        
        return [
            'user_id' => User::factory(),
            'title' => fake()->sentence(rand(4, 8)),
            'content' => fake()->paragraphs(rand(3, 8), true),
            'category' => fake()->randomElement($categories),
            'status' => fake()->randomElement(['published', 'draft']),
            'views' => fake()->numberBetween(0, 1000),
            'published_at' => fake()->optional(0.8)->dateTimeBetween('-6 months', 'now'),
        ];
    }

    /**
     * Indicate that the post is published.
     */
    public function published(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'published',
            'published_at' => fake()->dateTimeBetween('-6 months', 'now'),
        ]);
    }

    /**
     * Indicate that the post is a draft.
     */
    public function draft(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'draft',
            'published_at' => null,
        ]);
    }

    /**
     * Indicate that the post is popular (high views).
     */
    public function popular(): static
    {
        return $this->state(fn (array $attributes) => [
            'views' => fake()->numberBetween(500, 5000),
        ]);
    }

    /**
     * Set a specific category.
     */
    public function category(string $category): static
    {
        return $this->state(fn (array $attributes) => [
            'category' => $category,
        ]);
    }
}