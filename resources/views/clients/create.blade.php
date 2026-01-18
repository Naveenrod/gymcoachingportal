@extends('layouts.app')

@section('title', 'Add New Client')

@section('content')
<div class="page-header">
    <h1>Add New Client</h1>
    <a href="{{ route('clients.index') }}" class="btn btn-secondary">Back to Clients</a>
</div>

<div class="form-container">
    <form method="POST" action="{{ route('clients.store') }}">
        @csrf
        <div class="form-row">
            <div class="form-group">
                <label for="first_name">First Name *</label>
                <input type="text" id="first_name" name="first_name" value="{{ old('first_name') }}" required>
                @error('first_name')
                    <small style="color: var(--danger-color);">{{ $message }}</small>
                @enderror
            </div>

            <div class="form-group">
                <label for="last_name">Last Name *</label>
                <input type="text" id="last_name" name="last_name" value="{{ old('last_name') }}" required>
                @error('last_name')
                    <small style="color: var(--danger-color);">{{ $message }}</small>
                @enderror
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="email">Email *</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}" required>
                @error('email')
                    <small style="color: var(--danger-color);">{{ $message }}</small>
                @enderror
            </div>

            <div class="form-group">
                <label for="phone">Phone</label>
                <input type="tel" id="phone" name="phone" value="{{ old('phone') }}">
                @error('phone')
                    <small style="color: var(--danger-color);">{{ $message }}</small>
                @enderror
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="date_of_birth">Date of Birth</label>
                <input type="date" id="date_of_birth" name="date_of_birth" value="{{ old('date_of_birth') }}">
                @error('date_of_birth')
                    <small style="color: var(--danger-color);">{{ $message }}</small>
                @enderror
            </div>

            <div class="form-group">
                <label for="gender">Gender</label>
                <select id="gender" name="gender">
                    <option value="">Select Gender</option>
                    <option value="Male" {{ old('gender') == 'Male' ? 'selected' : '' }}>Male</option>
                    <option value="Female" {{ old('gender') == 'Female' ? 'selected' : '' }}>Female</option>
                    <option value="Other" {{ old('gender') == 'Other' ? 'selected' : '' }}>Other</option>
                </select>
                @error('gender')
                    <small style="color: var(--danger-color);">{{ $message }}</small>
                @enderror
            </div>
        </div>

        <div class="form-group">
            <label for="address">Address</label>
            <textarea id="address" name="address" rows="2">{{ old('address') }}</textarea>
            @error('address')
                <small style="color: var(--danger-color);">{{ $message }}</small>
            @enderror
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="emergency_contact_name">Emergency Contact Name</label>
                <input type="text" id="emergency_contact_name" name="emergency_contact_name" value="{{ old('emergency_contact_name') }}">
                @error('emergency_contact_name')
                    <small style="color: var(--danger-color);">{{ $message }}</small>
                @enderror
            </div>

            <div class="form-group">
                <label for="emergency_contact_phone">Emergency Contact Phone</label>
                <input type="tel" id="emergency_contact_phone" name="emergency_contact_phone" value="{{ old('emergency_contact_phone') }}">
                @error('emergency_contact_phone')
                    <small style="color: var(--danger-color);">{{ $message }}</small>
                @enderror
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="membership_type">Membership Type</label>
                <select id="membership_type" name="membership_type">
                    <option value="Basic" {{ old('membership_type', 'Basic') == 'Basic' ? 'selected' : '' }}>Basic</option>
                    <option value="Premium" {{ old('membership_type') == 'Premium' ? 'selected' : '' }}>Premium</option>
                    <option value="VIP" {{ old('membership_type') == 'VIP' ? 'selected' : '' }}>VIP</option>
                </select>
                @error('membership_type')
                    <small style="color: var(--danger-color);">{{ $message }}</small>
                @enderror
            </div>

            <div class="form-group">
                <label for="status">Status</label>
                <select id="status" name="status">
                    <option value="Active" {{ old('status', 'Active') == 'Active' ? 'selected' : '' }}>Active</option>
                    <option value="Inactive" {{ old('status') == 'Inactive' ? 'selected' : '' }}>Inactive</option>
                    <option value="On Hold" {{ old('status') == 'On Hold' ? 'selected' : '' }}>On Hold</option>
                </select>
                @error('status')
                    <small style="color: var(--danger-color);">{{ $message }}</small>
                @enderror
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="membership_start_date">Membership Start Date</label>
                <input type="date" id="membership_start_date" name="membership_start_date" value="{{ old('membership_start_date', date('Y-m-d')) }}">
                @error('membership_start_date')
                    <small style="color: var(--danger-color);">{{ $message }}</small>
                @enderror
            </div>

            <div class="form-group">
                <label for="membership_end_date">Membership End Date</label>
                <input type="date" id="membership_end_date" name="membership_end_date" value="{{ old('membership_end_date') }}">
                @error('membership_end_date')
                    <small style="color: var(--danger-color);">{{ $message }}</small>
                @enderror
            </div>
        </div>

        <div class="form-group">
            <label for="notes">Notes</label>
            <textarea id="notes" name="notes" rows="3">{{ old('notes') }}</textarea>
            @error('notes')
                <small style="color: var(--danger-color);">{{ $message }}</small>
            @enderror
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Add Client</button>
            <a href="{{ route('clients.index') }}" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>
@endsection
