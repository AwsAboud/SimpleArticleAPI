<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Article>
 */
class ArticleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $startDate = '-1 year'; // One year ago
        $endDate = 'now'; // Current date and time
        return [
            'title' => fake()->sentence(),
            'body' => fake()->paragraph(),
            'image' => fake()->imageUrl(),
            'published_at' => fake()->dateTimeBetween($startDate, $endDate),
        ];
    }
}
