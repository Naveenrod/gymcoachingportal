@extends('layouts.app')

@section('title', 'Appointment Details')

@section('content')
<div class="page-header">
    <h1>Appointment Details</h1>
    <div>
        <a href="{{ route('appointments.edit', $appointment) }}" class="btn btn-secondary">Edit</a>
        <a href="{{ route('appointments.index') }}" class="btn btn-secondary">Back to Appointments</a>
    </div>
</div>

<div class="detail-card">
    <div class="detail-grid">
        <div class="detail-item">
            <label>Client</label>
            @if($appointment->client)
                <p><a href="{{ route('clients.show', $appointment->client_id) }}">{{ $appointment->client->full_name }}</a></p>
            @else
                <p class="text-muted">Deleted client</p>
            @endif
        </div>
        <div class="detail-item">
            <label>Session Type</label>
            <p>{{ $appointment->session_type }}</p>
        </div>
        <div class="detail-item">
            <label>Date</label>
            <p>{{ $appointment->appointment_date->format('l, F j, Y') }}</p>
        </div>
        <div class="detail-item">
            <label>Time</label>
            <p>{{ \Carbon\Carbon::parse($appointment->appointment_time)->format('g:i A') }}</p>
        </div>
        <div class="detail-item">
            <label>Duration</label>
            <p>{{ $appointment->duration_minutes }} minutes</p>
        </div>
        <div class="detail-item">
            <label>Status</label>
            <p><span class="status status-{{ strtolower($appointment->status) }}">{{ $appointment->status }}</span></p>
        </div>
        @if($appointment->notes)
            <div class="detail-item full-width">
                <label>Notes</label>
                <p>{{ $appointment->notes }}</p>
            </div>
        @endif
    </div>
</div>
@endsection
