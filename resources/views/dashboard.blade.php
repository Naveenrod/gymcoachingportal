@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="page-header">
    <h1>Dashboard</h1>
    <p>Welcome back, {{ Auth::user()->full_name }}!</p>
</div>

<div class="stats-row">
    <div class="stat-card">
        <h3>{{ $totalClients }}</h3>
        <p>Total Clients</p>
        <small>{{ $activeClients }} Active</small>
    </div>
    <div class="stat-card">
        <h3>{{ $totalAppointments }}</h3>
        <p>Total Appointments</p>
        <small>{{ $upcomingAppointments }} Upcoming</small>
    </div>
    <div class="stat-card">
        <h3>{{ $todaysAppointments->count() }}</h3>
        <p>Today's Sessions</p>
        <small>{{ now()->format('l, F j') }}</small>
    </div>
</div>

<div class="dashboard-grid">
    <div class="dashboard-section">
        <h2>Today's Appointments - {{ now()->format('F j, Y') }}</h2>
        @if($todaysAppointments->isEmpty())
            <p class="empty-message">No appointments scheduled for today.</p>
        @else
            <div class="appointment-list">
                @foreach($todaysAppointments as $appointment)
                    <div class="appointment-card">
                        <div class="appointment-time">
                            <strong>{{ \Carbon\Carbon::parse($appointment->appointment_time)->format('g:i A') }}</strong>
                            <span>{{ $appointment->duration_minutes }} min</span>
                        </div>
                        <div class="appointment-details">
                            <h4>
                                <a href="{{ route('clients.show', $appointment->client_id) }}">
                                    {{ $appointment->client->full_name }}
                                </a>
                            </h4>
                            <p>{{ $appointment->session_type }}</p>
                            @if($appointment->client->phone)
                                <p class="phone">{{ $appointment->client->phone }}</p>
                            @endif
                            @if($appointment->notes)
                                <p class="notes">{{ $appointment->notes }}</p>
                            @endif
                        </div>
                        <div class="appointment-status">
                            <span class="status status-{{ strtolower($appointment->status) }}">{{ $appointment->status }}</span>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    <div class="dashboard-section">
        <h2>Upcoming Appointments</h2>
        @if($upcoming->isEmpty())
            <p class="empty-message">No upcoming appointments in the next 7 days.</p>
        @else
            <table class="compact-table">
                <tbody>
                    @foreach($upcoming as $appointment)
                        <tr>
                            <td>
                                <strong>{{ \Carbon\Carbon::parse($appointment->appointment_date)->format('M j') }}</strong><br>
                                <small>{{ \Carbon\Carbon::parse($appointment->appointment_time)->format('g:i A') }}</small>
                            </td>
                            <td>
                                <a href="{{ route('clients.show', $appointment->client_id) }}">
                                    {{ $appointment->client->full_name }}
                                </a><br>
                                <small>{{ $appointment->session_type }}</small>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>

    <div class="dashboard-section">
        <h2>Recent Clients</h2>
        @if($recentClients->isEmpty())
            <p class="empty-message">No clients yet.</p>
        @else
            <table class="compact-table">
                <tbody>
                    @foreach($recentClients as $client)
                        <tr>
                            <td>
                                <a href="{{ route('clients.show', $client->id) }}">
                                    {{ $client->full_name }}
                                </a><br>
                                <small>{{ $client->email }}</small>
                            </td>
                            <td>
                                <span class="badge badge-{{ strtolower($client->membership_type) }}">
                                    {{ $client->membership_type }}
                                </span>
                            </td>
                            <td>
                                <span class="status status-{{ strtolower(str_replace(' ', '-', $client->status)) }}">
                                    {{ $client->status }}
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <a href="{{ route('clients.index') }}" class="btn btn-link">View All Clients</a>
        @endif
    </div>
</div>
@endsection
