<?php

namespace Database\Factories;

use App\Models\Appointment;
use App\Models\Client;
use Illuminate\Database\Eloquent\Factories\Factory;

class AppointmentFactory extends Factory
{
    protected $model = Appointment::class;

    public function definition(): array
    {
        return [
            'client_id' => Client::factory(),
            'appointment_date' => fake()->dateTimeBetween('-1 month', '+1 month'),
            'appointment_time' => fake()->time('H:i'),
            'duration_minutes' => fake()->randomElement([30, 45, 60, 90]),
            'session_type' => fake()->randomElement(['Personal Training', 'Group Class', 'Consultation', 'Assessment']),
            'status' => fake()->randomElement(['Scheduled', 'Completed', 'Cancelled', 'No-Show']),
            'notes' => fake()->optional()->sentence(),
        ];
    }

    public function scheduled(): static
    {
        return $this->state([
            'status' => 'Scheduled',
            'appointment_date' => fake()->dateTimeBetween('now', '+1 month'),
        ]);
    }

    public function completed(): static
    {
        return $this->state([
            'status' => 'Completed',
            'appointment_date' => fake()->dateTimeBetween('-1 month', 'now'),
        ]);
    }
}
