@extends('layouts.app')

@section('title', 'Appointments')

@section('content')
<div class="page-header">
    <h1>Appointments</h1>
    <a href="{{ route('appointments.create') }}" class="btn btn-primary">New Appointment</a>
</div>

<div class="filters">
    <form method="GET" action="{{ route('appointments.index') }}" class="filter-form">
        <div class="form-row">
            <div class="form-group">
                <input type="text" name="search" placeholder="Search by client name..." value="{{ request('search') }}">
            </div>
            <div class="form-group">
                <select name="status">
                    <option value="">All Statuses</option>
                    <option value="Scheduled" {{ request('status') === 'Scheduled' ? 'selected' : '' }}>Scheduled</option>
                    <option value="Completed" {{ request('status') === 'Completed' ? 'selected' : '' }}>Completed</option>
                    <option value="Cancelled" {{ request('status') === 'Cancelled' ? 'selected' : '' }}>Cancelled</option>
                    <option value="No-Show" {{ request('status') === 'No-Show' ? 'selected' : '' }}>No-Show</option>
                </select>
            </div>
            <div class="form-group">
                <input type="date" name="date_from" value="{{ request('date_from') }}" placeholder="From date">
            </div>
            <div class="form-group">
                <input type="date" name="date_to" value="{{ request('date_to') }}" placeholder="To date">
            </div>
            <button type="submit" class="btn btn-secondary">Filter</button>
            @if(request()->hasAny(['search', 'status', 'date_from', 'date_to']))
                <a href="{{ route('appointments.index') }}" class="btn btn-link">Clear</a>
            @endif
        </div>
    </form>
</div>

<div class="table-container">
    <table class="data-table">
        <thead>
            <tr>
                <th>Date</th>
                <th>Time</th>
                <th>Client</th>
                <th>Type</th>
                <th>Duration</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($appointments as $appointment)
                <tr>
                    <td>{{ $appointment->appointment_date->format('M j, Y') }}</td>
                    <td>{{ \Carbon\Carbon::parse($appointment->appointment_time)->format('g:i A') }}</td>
                    <td>
                        @if($appointment->client)
                            <a href="{{ route('clients.show', $appointment->client_id) }}">{{ $appointment->client->full_name }}</a>
                        @else
                            <span class="text-muted">Deleted client</span>
                        @endif
                    </td>
                    <td>{{ $appointment->session_type }}</td>
                    <td>{{ $appointment->duration_minutes }} min</td>
                    <td><span class="status status-{{ strtolower($appointment->status) }}">{{ $appointment->status }}</span></td>
                    <td class="actions">
                        <a href="{{ route('appointments.show', $appointment) }}" class="btn btn-sm btn-info">View</a>
                        <a href="{{ route('appointments.edit', $appointment) }}" class="btn btn-sm btn-secondary">Edit</a>
                        <form method="POST" action="{{ route('appointments.destroy', $appointment) }}" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this appointment?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="empty-message">No appointments found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

{{ $appointments->appends(request()->query())->links() }}
@endsection
