<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Client;
use App\Models\Appointment;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $totalClients = Client::count();
        $activeClients = Client::where('status', 'Active')->count();
        $totalAppointments = Appointment::count();
        $upcomingAppointments = Appointment::where('appointment_date', '>=', Carbon::today())
            ->where('status', 'Scheduled')
            ->count();

        $today = Carbon::today();
        $todaysAppointments = Appointment::with('client')
            ->whereDate('appointment_date', $today)
            ->orderBy('appointment_time')
            ->get();

        $upcoming = Appointment::with('client')
            ->where('appointment_date', '>', $today)
            ->where('appointment_date', '<=', $today->copy()->addDays(7))
            ->where('status', 'Scheduled')
            ->orderBy('appointment_date')
            ->orderBy('appointment_time')
            ->limit(10)
            ->get();

        $recentClients = Client::orderBy('created_at', 'desc')->limit(5)->get();

        return view('dashboard', compact(
            'totalClients',
            'activeClients',
            'totalAppointments',
            'upcomingAppointments',
            'todaysAppointments',
            'upcoming',
            'recentClients'
        ));
    }
}
