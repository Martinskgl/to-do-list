<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class RoleFactory extends Factory
{
    public function definition(): array
    {
        $slug = $this->faker->randomElement(['user', 'admin']);

        return [
            'slug' => $slug,
            'label' => $slug,
        ];
    }
}
