@extends('layouts.app')

@section('title', 'Client Details')

@section('content')
<div class="page-header">
    <h1>Client Details</h1>
    <div>
        <a href="{{ route('clients.edit', $client) }}" class="btn btn-secondary">Edit</a>
        <a href="{{ route('clients.index') }}" class="btn btn-secondary">Back to Clients</a>
    </div>
</div>

<div class="client-details">
    <div class="details-section">
        <h2>Personal Information</h2>
        <div class="details-grid">
            <div class="detail-item">
                <label>Name:</label>
                <span>{{ $client->full_name }}</span>
            </div>
            <div class="detail-item">
                <label>Email:</label>
                <span>{{ $client->email }}</span>
            </div>
            <div class="detail-item">
                <label>Phone:</label>
                <span>{{ $client->phone ?: 'N/A' }}</span>
            </div>
            <div class="detail-item">
                <label>Date of Birth:</label>
                <span>{{ $client->date_of_birth ? $client->date_of_birth->format('F d, Y') : 'N/A' }}</span>
            </div>
            <div class="detail-item">
                <label>Gender:</label>
                <span>{{ $client->gender ?: 'N/A' }}</span>
            </div>
            <div class="detail-item">
                <label>Address:</label>
                <span>{{ $client->address ?: 'N/A' }}</span>
            </div>
        </div>
    </div>

    <div class="details-section">
        <h2>Emergency Contact</h2>
        <div class="details-grid">
            <div class="detail-item">
                <label>Name:</label>
                <span>{{ $client->emergency_contact_name ?: 'N/A' }}</span>
            </div>
            <div class="detail-item">
                <label>Phone:</label>
                <span>{{ $client->emergency_contact_phone ?: 'N/A' }}</span>
            </div>
        </div>
    </div>

    <div class="details-section">
        <h2>Membership Information</h2>
        <div class="details-grid">
            <div class="detail-item">
                <label>Membership Type:</label>
                <span><span class="badge badge-{{ strtolower($client->membership_type) }}">{{ $client->membership_type }}</span></span>
            </div>
            <div class="detail-item">
                <label>Status:</label>
                <span><span class="status status-{{ strtolower(str_replace(' ', '-', $client->status)) }}">{{ $client->status }}</span></span>
            </div>
            <div class="detail-item">
                <label>Start Date:</label>
                <span>{{ $client->membership_start_date ? $client->membership_start_date->format('F d, Y') : 'N/A' }}</span>
            </div>
            <div class="detail-item">
                <label>End Date:</label>
                <span>{{ $client->membership_end_date ? $client->membership_end_date->format('F d, Y') : 'N/A' }}</span>
            </div>
        </div>
    </div>

    @if($client->notes)
    <div class="details-section">
        <h2>Notes</h2>
        <p>{{ nl2br(e($client->notes)) }}</p>
    </div>
    @endif

    <div class="details-section">
        <h2>Recent Appointments</h2>
        @if($client->appointments->isEmpty())
            <p>No appointments scheduled yet.</p>
        @else
            <table>
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Time</th>
                        <th>Duration</th>
                        <th>Type</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($client->appointments->take(10) as $appointment)
                        <tr>
                            <td>{{ $appointment->appointment_date->format('M d, Y') }}</td>
                            <td>{{ \Carbon\Carbon::parse($appointment->appointment_time)->format('g:i A') }}</td>
                            <td>{{ $appointment->duration_minutes }} min</td>
                            <td>{{ $appointment->session_type }}</td>
                            <td><span class="status status-{{ strtolower($appointment->status) }}">{{ $appointment->status }}</span></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
</div>
@endsection
