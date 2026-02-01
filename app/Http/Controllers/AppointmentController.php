<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAppointmentRequest;
use App\Http\Requests\UpdateAppointmentRequest;
use App\Models\Appointment;
use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AppointmentController extends Controller
{
    public function index(Request $request)
    {
        $query = Appointment::with('client');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('client', function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('date_from')) {
            $query->where('appointment_date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->where('appointment_date', '<=', $request->date_to);
        }

        $appointments = $query->orderBy('appointment_date', 'desc')
            ->orderBy('appointment_time', 'desc')
            ->paginate(25);

        return view('appointments.index', compact('appointments'));
    }

    public function create(Request $request)
    {
        $clients = Client::orderBy('last_name')->orderBy('first_name')->get();
        $selectedClientId = $request->get('client_id');
        return view('appointments.create', compact('clients', 'selectedClientId'));
    }

    public function store(StoreAppointmentRequest $request)
    {
        $appointment = DB::transaction(function () use ($request) {
            return Appointment::create($request->validated());
        });

        Log::info('Appointment created', ['appointment_id' => $appointment->id, 'client_id' => $appointment->client_id, 'user_id' => Auth::id()]);

        return redirect()->route('appointments.index')->with('success', 'Appointment created successfully.');
    }

    public function show(Appointment $appointment)
    {
        $appointment->load('client');
        return view('appointments.show', compact('appointment'));
    }

    public function edit(Appointment $appointment)
    {
        $clients = Client::orderBy('last_name')->orderBy('first_name')->get();
        return view('appointments.edit', compact('appointment', 'clients'));
    }

    public function update(UpdateAppointmentRequest $request, Appointment $appointment)
    {
        DB::transaction(function () use ($appointment, $request) {
            $appointment->update($request->validated());
        });

        Log::info('Appointment updated', ['appointment_id' => $appointment->id, 'user_id' => Auth::id()]);

        return redirect()->route('appointments.index')->with('success', 'Appointment updated successfully.');
    }

    public function destroy(Appointment $appointment)
    {
        Log::warning('Appointment deleted', ['appointment_id' => $appointment->id, 'client_id' => $appointment->client_id, 'user_id' => Auth::id()]);

        $appointment->delete();

        return redirect()->route('appointments.index')->with('success', 'Appointment deleted successfully.');
    }
}
