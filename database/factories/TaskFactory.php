<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;
use App\Enums\TaskStatus;

class TaskFactory extends Factory
{
    public function definition(): array
    {
        $title = $this->faker->sentence(3);
        return [
            'title' => $title,
            'describe' => $this->faker->paragraph(),
            'slug' => str($title)->slug(),
            'status' => $this->faker->randomElement(['pending', 'in_progress', 'completed', 'cancelled']),
            'user_id' => User::factory(),
            'expiration_date' => $this->faker->optional()->dateTimeBetween('now', '+1 month'),
        ];
    }
}