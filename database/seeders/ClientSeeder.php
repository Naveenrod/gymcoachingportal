<?php

namespace Database\Seeders;

use App\Models\Client;
use App\Models\User;
use Illuminate\Database\Seeder;

class ClientSeeder extends Seeder
{
    public function run(): void
    {
        // --- 3 clients linked to client user accounts ---

        $client1User = User::where('username', 'client1')->first();
        Client::create([
            'user_id' => $client1User->id,
            'first_name' => 'Jane',
            'last_name' => 'Doe',
            'email' => 'jane.doe@example.com',
            'phone' => '(555) 101-2001',
            'date_of_birth' => '1992-03-15',
            'gender' => 'Female',
            'address' => '123 Fitness Lane, Los Angeles, CA 90001',
            'emergency_contact_name' => 'Mark Doe',
            'emergency_contact_phone' => '(555) 101-9999',
            'membership_type' => 'Premium',
            'membership_start_date' => now()->subMonths(6)->toDateString(),
            'membership_end_date' => now()->addMonths(6)->toDateString(),
            'status' => 'Active',
            'notes' => 'Focused on strength training and weight loss. Prefers morning sessions.',
            'loom_link' => 'https://www.loom.com/share/example-jane-checkin',
            'package' => 'Premium 3x/week',
            'check_in_frequency' => 'Weekly',
            'check_in_day' => 'Monday',
            'submitted' => 'Submitted',
            'rank' => 'Gold',
        ]);

        $client2User = User::where('username', 'client2')->first();
        Client::create([
            'user_id' => $client2User->id,
            'first_name' => 'John',
            'last_name' => 'Smith',
            'email' => 'john.smith@example.com',
            'phone' => '(555) 202-3002',
            'date_of_birth' => '1988-07-22',
            'gender' => 'Male',
            'address' => '456 Muscle Ave, San Diego, CA 92101',
            'emergency_contact_name' => 'Lisa Smith',
            'emergency_contact_phone' => '(555) 202-9999',
            'membership_type' => 'VIP',
            'membership_start_date' => now()->subMonths(12)->toDateString(),
            'membership_end_date' => now()->addMonths(3)->toDateString(),
            'status' => 'Active',
            'notes' => 'Training for marathon. Needs cardio-focused plan.',
            'loom_link' => 'https://www.loom.com/share/example-john-checkin',
            'package' => 'VIP Unlimited',
            'check_in_frequency' => 'Bi-weekly',
            'check_in_day' => 'Wednesday',
            'submitted' => 'Submitted',
            'rank' => 'Platinum',
        ]);

        $client3User = User::where('username', 'client3')->first();
        Client::create([
            'user_id' => $client3User->id,
            'first_name' => 'Emily',
            'last_name' => 'Davis',
            'email' => 'emily.davis@example.com',
            'phone' => '(555) 303-4003',
            'date_of_birth' => '1995-11-08',
            'gender' => 'Female',
            'address' => '789 Wellness Blvd, Phoenix, AZ 85001',
            'emergency_contact_name' => 'Tom Davis',
            'emergency_contact_phone' => '(555) 303-9999',
            'membership_type' => 'Basic',
            'membership_start_date' => now()->subMonths(2)->toDateString(),
            'membership_end_date' => now()->addMonths(10)->toDateString(),
            'status' => 'Active',
            'notes' => 'New member. Starting with basic fitness assessment.',
            'package' => 'Basic 2x/week',
            'check_in_frequency' => 'Weekly',
            'check_in_day' => 'Friday',
            'submitted' => '',
            'rank' => 'Silver',
        ]);

        // --- 7 coach-managed clients (no user accounts) ---

        Client::create([
            'first_name' => 'Michael',
            'last_name' => 'Johnson',
            'email' => 'michael.j@example.com',
            'phone' => '(555) 404-5004',
            'date_of_birth' => '1985-01-30',
            'gender' => 'Male',
            'address' => '321 Power St, Denver, CO 80201',
            'emergency_contact_name' => 'Karen Johnson',
            'emergency_contact_phone' => '(555) 404-9999',
            'membership_type' => 'Premium',
            'membership_start_date' => now()->subMonths(8)->toDateString(),
            'membership_end_date' => now()->addMonths(4)->toDateString(),
            'status' => 'Active',
            'notes' => 'Experienced lifter. Competition prep.',
            'loom_link' => 'https://www.loom.com/share/example-michael-checkin',
            'package' => 'Premium 4x/week',
            'check_in_frequency' => 'Weekly',
            'check_in_day' => 'Tuesday',
            'submitted' => 'Submitted',
            'rank' => 'Gold',
        ]);

        Client::create([
            'first_name' => 'Sarah',
            'last_name' => 'Williams',
            'email' => 'sarah.w@example.com',
            'phone' => '(555) 505-6005',
            'date_of_birth' => '1990-05-12',
            'gender' => 'Female',
            'address' => '654 Health Way, Seattle, WA 98101',
            'emergency_contact_name' => 'David Williams',
            'emergency_contact_phone' => '(555) 505-9999',
            'membership_type' => 'Premium',
            'membership_start_date' => now()->subMonths(4)->toDateString(),
            'membership_end_date' => now()->addMonths(8)->toDateString(),
            'status' => 'Active',
            'notes' => 'Post-pregnancy fitness recovery. Gentle approach needed.',
            'package' => 'Premium 3x/week',
            'check_in_frequency' => 'Weekly',
            'check_in_day' => 'Thursday',
            'submitted' => '',
            'rank' => 'Silver',
        ]);

        Client::create([
            'first_name' => 'Robert',
            'last_name' => 'Brown',
            'email' => 'robert.b@example.com',
            'phone' => '(555) 606-7006',
            'date_of_birth' => '1978-09-25',
            'gender' => 'Male',
            'address' => '987 Gym Road, Austin, TX 73301',
            'emergency_contact_name' => 'Patricia Brown',
            'emergency_contact_phone' => '(555) 606-9999',
            'membership_type' => 'Basic',
            'membership_start_date' => now()->subMonths(3)->toDateString(),
            'membership_end_date' => now()->addMonths(9)->toDateString(),
            'status' => 'Inactive',
            'notes' => 'On medical leave. Will resume next month.',
            'package' => 'Basic 2x/week',
            'check_in_frequency' => 'Monthly',
            'check_in_day' => 'Monday',
            'submitted' => '',
            'rank' => '',
        ]);

        Client::create([
            'first_name' => 'Amanda',
            'last_name' => 'Martinez',
            'email' => 'amanda.m@example.com',
            'phone' => '(555) 707-8007',
            'date_of_birth' => '1993-12-03',
            'gender' => 'Female',
            'address' => '147 Cardio Circle, Miami, FL 33101',
            'emergency_contact_name' => 'Carlos Martinez',
            'emergency_contact_phone' => '(555) 707-9999',
            'membership_type' => 'VIP',
            'membership_start_date' => now()->subMonths(10)->toDateString(),
            'membership_end_date' => now()->addMonths(2)->toDateString(),
            'status' => 'Active',
            'notes' => 'Yoga and pilates focus. Also does personal training.',
            'loom_link' => 'https://www.loom.com/share/example-amanda-checkin',
            'package' => 'VIP Unlimited',
            'check_in_frequency' => 'Weekly',
            'check_in_day' => 'Wednesday',
            'submitted' => 'Submitted',
            'rank' => 'Platinum',
        ]);

        Client::create([
            'first_name' => 'David',
            'last_name' => 'Wilson',
            'email' => 'david.w@example.com',
            'phone' => '(555) 808-9008',
            'date_of_birth' => '1982-04-18',
            'gender' => 'Male',
            'address' => '258 Sprint Lane, Chicago, IL 60601',
            'emergency_contact_name' => 'Nancy Wilson',
            'emergency_contact_phone' => '(555) 808-9999',
            'membership_type' => 'Basic',
            'membership_start_date' => now()->subMonths(1)->toDateString(),
            'membership_end_date' => now()->addMonths(11)->toDateString(),
            'status' => 'On Hold',
            'notes' => 'Traveling for work. Hold until March.',
            'package' => 'Basic 2x/week',
            'check_in_frequency' => 'Monthly',
            'check_in_day' => 'Friday',
            'submitted' => '',
            'rank' => '',
        ]);

        Client::create([
            'first_name' => 'Jessica',
            'last_name' => 'Taylor',
            'email' => 'jessica.t@example.com',
            'phone' => '(555) 909-1009',
            'date_of_birth' => '1997-08-07',
            'gender' => 'Female',
            'address' => '369 Flex Ave, Portland, OR 97201',
            'emergency_contact_name' => 'Brian Taylor',
            'emergency_contact_phone' => '(555) 909-9999',
            'membership_type' => 'Premium',
            'membership_start_date' => now()->subMonths(5)->toDateString(),
            'membership_end_date' => now()->addMonths(7)->toDateString(),
            'status' => 'Inactive',
            'notes' => 'Cancelled membership. May return in summer.',
            'package' => 'Premium 3x/week',
            'check_in_frequency' => 'Weekly',
            'check_in_day' => 'Tuesday',
            'submitted' => '',
            'rank' => 'Bronze',
        ]);

        Client::create([
            'first_name' => 'Chris',
            'last_name' => 'Anderson',
            'email' => 'chris.a@example.com',
            'phone' => '(555) 010-2010',
            'date_of_birth' => '1991-02-14',
            'gender' => 'Male',
            'address' => '741 Strength Blvd, Nashville, TN 37201',
            'emergency_contact_name' => 'Jenny Anderson',
            'emergency_contact_phone' => '(555) 010-9999',
            'membership_type' => 'Basic',
            'membership_start_date' => now()->subWeeks(3)->toDateString(),
            'membership_end_date' => now()->addMonths(12)->toDateString(),
            'status' => 'On Hold',
            'notes' => 'New signup. Awaiting initial assessment appointment.',
            'package' => 'Basic 1x/week',
            'check_in_frequency' => 'Bi-weekly',
            'check_in_day' => 'Thursday',
            'submitted' => '',
            'rank' => '',
        ]);
    }
}
