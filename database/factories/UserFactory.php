<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class UserFactory extends Factory
{
    protected $model = User::class;

    public function definition(): array
    {
        return [
            'username' => fake()->unique()->userName(),
            'password' => 'password',
            'email' => fake()->unique()->safeEmail(),
            'full_name' => fake()->name(),
            'role' => 'coach',
        ];
    }

    public function admin(): static
    {
        return $this->state(['role' => 'admin']);
    }

    public function coach(): static
    {
        return $this->state(['role' => 'coach']);
    }

    public function client(): static
    {
        return $this->state(['role' => 'client']);
    }
}
