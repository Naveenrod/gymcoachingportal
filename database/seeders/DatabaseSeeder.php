<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create default admin user (password: admin123)
        User::create([
            'username' => 'admin',
            'password' => Hash::make('admin123'),
            'email' => 'admin@gymcoach.com',
            'full_name' => 'Admin User',
        ]);
    }
}
