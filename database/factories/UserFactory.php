<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

class UserFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => fake()->firstName().' '.fake()->lastName(),
            'gender' => fake()->randomElement(['M', 'F']),
            'birth_date' => fake()->dateTimeBetween('1960-01-01', '2000-12-31')->format('Y-m-d'),
            'email' => fake()->unique()->safeEmail(),
            'login' => fake()->unique()->bothify('user_####'),
            'password' => Hash::make('password'),
            'role' => 'user',
        ];
    }

    public function admin(): static
    {
        return $this->state(['role' => 'admin']);
    }
}
