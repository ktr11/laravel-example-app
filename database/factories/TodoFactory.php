<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class TodoFactory extends Factory
{
    public function definition(): array
    {
        return [
            'title' => fake()->sentence(4),
            'completed' => false,
        ];
    }

    public function completed(): static
    {
        return $this->state(['completed' => true]);
    }
}
