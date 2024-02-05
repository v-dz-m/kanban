<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Task>
 */
class TaskFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'board_id' => 1,
            'title' => fake()->words(5, true),
            'description' => fake()->paragraph(3),
            'status' => fake()->numberBetween(1, 6),
            'order' => 1,
            'author_user_id' => 1,
            'executor_user_id' => 1
        ];
    }
}
