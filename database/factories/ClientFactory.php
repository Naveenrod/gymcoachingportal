<?php

namespace Database\Factories;

use App\Models\Client;
use Illuminate\Database\Eloquent\Factories\Factory;

class ClientFactory extends Factory
{
    protected $model = Client::class;

    public function definition(): array
    {
        return [
            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'email' => fake()->unique()->safeEmail(),
            'phone' => fake()->phoneNumber(),
            'date_of_birth' => fake()->dateTimeBetween('-60 years', '-18 years'),
            'gender' => fake()->randomElement(['Male', 'Female', 'Other']),
            'address' => fake()->address(),
            'emergency_contact_name' => fake()->name(),
            'emergency_contact_phone' => fake()->phoneNumber(),
            'membership_type' => fake()->randomElement(['Basic', 'Premium', 'VIP']),
            'membership_start_date' => fake()->dateTimeBetween('-1 year', 'now'),
            'membership_end_date' => fake()->dateTimeBetween('now', '+1 year'),
            'notes' => fake()->optional()->sentence(),
            'status' => fake()->randomElement(['Active', 'Inactive', 'On Hold']),
        ];
    }

    public function active(): static
    {
        return $this->state(['status' => 'Active']);
    }

    public function inactive(): static
    {
        return $this->state(['status' => 'Inactive']);
    }
}
