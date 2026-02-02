@extends('layouts.app')

@section('title', 'Edit Appointment')

@section('content')
<div class="page-header">
    <h1>Edit Appointment</h1>
    <a href="{{ route('appointments.index') }}" class="btn btn-secondary">Back to Appointments</a>
</div>

<div class="form-container">
    <form method="POST" action="{{ route('appointments.update', $appointment) }}">
        @csrf
        @method('PUT')

        <div class="form-row">
            <div class="form-group">
                <label for="client_id">Client <span class="required">*</span></label>
                <select id="client_id" name="client_id" required>
                    <option value="">Select Client</option>
                    @foreach($clients as $client)
                        <option value="{{ $client->id }}" {{ old('client_id', $appointment->client_id) == $client->id ? 'selected' : '' }}>
                            {{ $client->full_name }} ({{ $client->email }})
                        </option>
                    @endforeach
                </select>
                @error('client_id') <span class="error">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
                <label for="session_type">Session Type <span class="required">*</span></label>
                <select id="session_type" name="session_type" required>
                    <option value="Personal Training" {{ old('session_type', $appointment->session_type) === 'Personal Training' ? 'selected' : '' }}>Personal Training</option>
                    <option value="Group Class" {{ old('session_type', $appointment->session_type) === 'Group Class' ? 'selected' : '' }}>Group Class</option>
                    <option value="Consultation" {{ old('session_type', $appointment->session_type) === 'Consultation' ? 'selected' : '' }}>Consultation</option>
                    <option value="Assessment" {{ old('session_type', $appointment->session_type) === 'Assessment' ? 'selected' : '' }}>Assessment</option>
                </select>
                @error('session_type') <span class="error">{{ $message }}</span> @enderror
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="appointment_date">Date <span class="required">*</span></label>
                <input type="date" id="appointment_date" name="appointment_date" value="{{ old('appointment_date', $appointment->appointment_date->format('Y-m-d')) }}" required>
                @error('appointment_date') <span class="error">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
                <label for="appointment_time">Time <span class="required">*</span></label>
                <input type="time" id="appointment_time" name="appointment_time" value="{{ old('appointment_time', \Carbon\Carbon::parse($appointment->appointment_time)->format('H:i')) }}" required>
                @error('appointment_time') <span class="error">{{ $message }}</span> @enderror
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="duration_minutes">Duration (minutes) <span class="required">*</span></label>
                <input type="number" id="duration_minutes" name="duration_minutes" value="{{ old('duration_minutes', $appointment->duration_minutes) }}" min="15" max="240" required>
                @error('duration_minutes') <span class="error">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
                <label for="status">Status <span class="required">*</span></label>
                <select id="status" name="status" required>
                    <option value="Scheduled" {{ old('status', $appointment->status) === 'Scheduled' ? 'selected' : '' }}>Scheduled</option>
                    <option value="Completed" {{ old('status', $appointment->status) === 'Completed' ? 'selected' : '' }}>Completed</option>
                    <option value="Cancelled" {{ old('status', $appointment->status) === 'Cancelled' ? 'selected' : '' }}>Cancelled</option>
                    <option value="No-Show" {{ old('status', $appointment->status) === 'No-Show' ? 'selected' : '' }}>No-Show</option>
                </select>
                @error('status') <span class="error">{{ $message }}</span> @enderror
            </div>
        </div>

        <div class="form-group">
            <label for="notes">Notes</label>
            <textarea id="notes" name="notes" rows="3" maxlength="2000">{{ old('notes', $appointment->notes) }}</textarea>
            @error('notes') <span class="error">{{ $message }}</span> @enderror
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Update Appointment</button>
            <a href="{{ route('appointments.index') }}" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>
@endsection
