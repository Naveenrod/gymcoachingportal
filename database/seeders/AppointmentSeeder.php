<?php

namespace Database\Seeders;

use App\Models\Appointment;
use App\Models\Client;
use Illuminate\Database\Seeder;

class AppointmentSeeder extends Seeder
{
    public function run(): void
    {
        $clients = Client::all();

        if ($clients->isEmpty()) {
            $this->command->warn('No clients found. Skipping appointment seeding.');

            return;
        }

        // --- Future appointments (Scheduled) ---
        foreach ($clients->take(7) as $index => $client) {
            Appointment::create([
                'client_id' => $client->id,
                'appointment_date' => now()->addDays($index + 1)->toDateString(),
                'appointment_time' => sprintf('%02d:00', 8 + $index),
                'duration_minutes' => [30, 45, 60, 60, 90, 45, 60][$index],
                'session_type' => ['Personal Training', 'Group Class', 'Consultation', 'Personal Training', 'Assessment', 'Group Class', 'Personal Training'][$index],
                'status' => 'Scheduled',
                'notes' => [
                    'Upper body focus day',
                    'Morning group HIIT session',
                    'Nutrition planning meeting',
                    'Leg day + core work',
                    'Quarterly fitness assessment',
                    'Afternoon yoga class',
                    'Full body strength circuit',
                ][$index],
            ]);
        }

        // A few more scheduled ones spread out
        Appointment::create([
            'client_id' => $clients[0]->id,
            'appointment_date' => now()->addDays(8)->toDateString(),
            'appointment_time' => '10:30',
            'duration_minutes' => 60,
            'session_type' => 'Personal Training',
            'status' => 'Scheduled',
            'notes' => 'Follow-up session - progressive overload',
        ]);

        Appointment::create([
            'client_id' => $clients[1]->id,
            'appointment_date' => now()->addDays(10)->toDateString(),
            'appointment_time' => '14:00',
            'duration_minutes' => 45,
            'session_type' => 'Consultation',
            'status' => 'Scheduled',
            'notes' => 'Marathon training plan review',
        ]);

        Appointment::create([
            'client_id' => $clients[3]->id,
            'appointment_date' => now()->addDays(14)->toDateString(),
            'appointment_time' => '09:00',
            'duration_minutes' => 90,
            'session_type' => 'Assessment',
            'status' => 'Scheduled',
            'notes' => 'Pre-competition body composition check',
        ]);

        // --- Past appointments (Completed) ---
        foreach ($clients->take(8) as $index => $client) {
            Appointment::create([
                'client_id' => $client->id,
                'appointment_date' => now()->subDays($index + 2)->toDateString(),
                'appointment_time' => sprintf('%02d:00', 9 + ($index % 4)),
                'duration_minutes' => [60, 45, 60, 30, 90, 60, 45, 60][$index],
                'session_type' => ['Personal Training', 'Personal Training', 'Group Class', 'Consultation', 'Assessment', 'Personal Training', 'Group Class', 'Personal Training'][$index],
                'status' => 'Completed',
                'notes' => [
                    'Great session! Increased bench press by 10lbs.',
                    'Ran 5K in under 25 minutes. Good progress.',
                    'Group HIIT - all participants did well.',
                    'Discussed meal prep strategies.',
                    'Baseline measurements recorded.',
                    'Deadlift form improved significantly.',
                    'Core and flexibility work completed.',
                    'Cardio endurance test - excellent results.',
                ][$index],
            ]);
        }

        // Two more completed from further back
        Appointment::create([
            'client_id' => $clients[0]->id,
            'appointment_date' => now()->subDays(15)->toDateString(),
            'appointment_time' => '08:00',
            'duration_minutes' => 60,
            'session_type' => 'Personal Training',
            'status' => 'Completed',
            'notes' => 'Initial strength assessment. Set baseline numbers.',
        ]);

        Appointment::create([
            'client_id' => $clients[1]->id,
            'appointment_date' => now()->subDays(20)->toDateString(),
            'appointment_time' => '16:00',
            'duration_minutes' => 45,
            'session_type' => 'Consultation',
            'status' => 'Completed',
            'notes' => 'Created personalized marathon training schedule.',
        ]);

        // --- Cancelled appointments ---
        Appointment::create([
            'client_id' => $clients[5]->id,
            'appointment_date' => now()->subDays(5)->toDateString(),
            'appointment_time' => '11:00',
            'duration_minutes' => 60,
            'session_type' => 'Personal Training',
            'status' => 'Cancelled',
            'notes' => 'Client cancelled - family emergency.',
        ]);

        Appointment::create([
            'client_id' => $clients[6]->id,
            'appointment_date' => now()->subDays(3)->toDateString(),
            'appointment_time' => '15:00',
            'duration_minutes' => 45,
            'session_type' => 'Group Class',
            'status' => 'Cancelled',
            'notes' => 'Cancelled due to coach illness.',
        ]);

        Appointment::create([
            'client_id' => $clients[2]->id,
            'appointment_date' => now()->subDays(7)->toDateString(),
            'appointment_time' => '13:00',
            'duration_minutes' => 30,
            'session_type' => 'Consultation',
            'status' => 'Cancelled',
            'notes' => 'Rescheduled to next week.',
        ]);

        // --- No-Show appointments ---
        Appointment::create([
            'client_id' => $clients[6]->id,
            'appointment_date' => now()->subDays(8)->toDateString(),
            'appointment_time' => '10:00',
            'duration_minutes' => 60,
            'session_type' => 'Personal Training',
            'status' => 'No-Show',
            'notes' => 'Client did not show up. Second no-show this month.',
        ]);

        Appointment::create([
            'client_id' => $clients[7]->id,
            'appointment_date' => now()->subDays(4)->toDateString(),
            'appointment_time' => '09:30',
            'duration_minutes' => 45,
            'session_type' => 'Personal Training',
            'status' => 'No-Show',
            'notes' => 'No response to confirmation call.',
        ]);
    }
}
