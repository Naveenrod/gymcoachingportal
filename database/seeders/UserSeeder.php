<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Admin user
        User::factory()->admin()->create([
            'username' => 'admin',
            'email' => 'admin@gymcoach.com',
            'full_name' => 'Admin User',
            'password' => 'admin123',
        ]);

        // Coach users
        User::factory()->coach()->create([
            'username' => 'coach',
            'email' => 'sarah@gymcoach.com',
            'full_name' => 'Sarah Coach',
            'password' => 'coach123',
        ]);

        User::factory()->coach()->create([
            'username' => 'coach2',
            'email' => 'mike@gymcoach.com',
            'full_name' => 'Mike Trainer',
            'password' => 'coach123',
        ]);

        // Client users (will be linked to Client records in ClientSeeder)
        User::factory()->client()->create([
            'username' => 'client1',
            'email' => 'jane@example.com',
            'full_name' => 'Jane Doe',
            'password' => 'client123',
        ]);

        User::factory()->client()->create([
            'username' => 'client2',
            'email' => 'john@example.com',
            'full_name' => 'John Smith',
            'password' => 'client123',
        ]);

        User::factory()->client()->create([
            'username' => 'client3',
            'email' => 'emily@example.com',
            'full_name' => 'Emily Davis',
            'password' => 'client123',
        ]);
    }
}
