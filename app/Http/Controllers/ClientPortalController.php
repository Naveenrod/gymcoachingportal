<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class ClientPortalController extends Controller
{
    public function dashboard()
    {
        $client = Auth::user()->client;

        if (! $client) {
            return view('portal.no-profile');
        }

        $upcomingAppointments = $client->appointments()
            ->where('appointment_date', '>=', Carbon::today())
            ->where('status', 'Scheduled')
            ->orderBy('appointment_date')
            ->orderBy('appointment_time')
            ->limit(10)
            ->get();

        return view('portal.dashboard', compact('client', 'upcomingAppointments'));
    }

    public function appointments()
    {
        $client = Auth::user()->client;

        if (! $client) {
            return view('portal.no-profile');
        }

        $appointments = $client->appointments()
            ->orderBy('appointment_date', 'desc')
            ->orderBy('appointment_time', 'desc')
            ->paginate(25);

        return view('portal.appointments', compact('client', 'appointments'));
    }

    public function profile()
    {
        $client = Auth::user()->client;

        if (! $client) {
            return view('portal.no-profile');
        }

        return view('portal.profile', compact('client'));
    }
}
