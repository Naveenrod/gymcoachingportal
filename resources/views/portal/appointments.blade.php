@extends('layouts.app')

@section('title', 'My Appointments')

@section('content')
<div class="page-header">
    <h1>My Appointments</h1>
</div>

<div class="table-responsive">
<div class="table-container">
    <table class="data-table">
        <thead>
            <tr>
                <th>Date</th>
                <th>Time</th>
                <th>Type</th>
                <th>Duration</th>
                <th>Status</th>
                <th>Notes</th>
            </tr>
        </thead>
        <tbody>
            @forelse($appointments as $appointment)
                <tr>
                    <td>{{ $appointment->appointment_date->format('M j, Y') }}</td>
                    <td>{{ \Carbon\Carbon::parse($appointment->appointment_time)->format('g:i A') }}</td>
                    <td>{{ $appointment->session_type }}</td>
                    <td>{{ $appointment->duration_minutes }} min</td>
                    <td><span class="status status-{{ strtolower($appointment->status) }}">{{ $appointment->status }}</span></td>
                    <td>{{ $appointment->notes ?: '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="empty-message">No appointments found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
</div>

{{ $appointments->links() }}
@endsection
