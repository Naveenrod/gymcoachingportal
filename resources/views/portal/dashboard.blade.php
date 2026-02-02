@extends('layouts.app')

@section('title', 'My Dashboard')

@section('content')
<div class="page-header">
    <h1>Welcome, {{ $client->full_name }}</h1>
    <p>Your coaching dashboard</p>
</div>

<div class="stats-row">
    <div class="stat-card">
        <h3>{{ $client->membership_type }}</h3>
        <p>Membership</p>
        <small>Status: {{ $client->status }}</small>
    </div>
    <div class="stat-card">
        <h3>{{ $upcomingAppointments->count() }}</h3>
        <p>Upcoming Sessions</p>
    </div>
    @if($client->membership_end_date)
        <div class="stat-card">
            <h3>{{ $client->membership_end_date->format('M j, Y') }}</h3>
            <p>Membership Expires</p>
        </div>
    @endif
</div>

<div class="dashboard-section">
    <h2>Upcoming Appointments</h2>
    @if($upcomingAppointments->isEmpty())
        <p class="empty-message">No upcoming appointments scheduled.</p>
    @else
        <div class="appointment-list">
            @foreach($upcomingAppointments as $appointment)
                <div class="appointment-card">
                    <div class="appointment-time">
                        <strong>{{ $appointment->appointment_date->format('M j') }}</strong>
                        <span>{{ \Carbon\Carbon::parse($appointment->appointment_time)->format('g:i A') }}</span>
                    </div>
                    <div class="appointment-details">
                        <h4>{{ $appointment->session_type }}</h4>
                        <p>{{ $appointment->duration_minutes }} minutes</p>
                    </div>
                    <div class="appointment-status">
                        <span class="status status-{{ strtolower($appointment->status) }}">{{ $appointment->status }}</span>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
