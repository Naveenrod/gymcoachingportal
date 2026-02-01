<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::factory()->admin()->create([
            'username' => 'admin',
            'email' => 'admin@gymcoach.com',
            'full_name' => 'Admin User',
            'password' => 'admin123',
        ]);

        User::factory()->coach()->create([
            'username' => 'coach',
            'email' => 'coach@gymcoach.com',
            'full_name' => 'Sample Coach',
            'password' => 'coach123',
        ]);
    }
}
