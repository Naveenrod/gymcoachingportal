@extends('layouts.app')

@section('title', 'Add User')

@section('content')
<div class="page-header">
    <h1>Add User</h1>
    <a href="{{ route('users.index') }}" class="btn btn-secondary">Back to Users</a>
</div>

<div class="form-container">
    <form method="POST" action="{{ route('users.store') }}">
        @csrf

        <div class="form-row">
            <div class="form-group">
                <label for="full_name">Full Name <span class="required">*</span></label>
                <input type="text" id="full_name" name="full_name" value="{{ old('full_name') }}" required maxlength="100">
                @error('full_name') <span class="error">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
                <label for="username">Username <span class="required">*</span></label>
                <input type="text" id="username" name="username" value="{{ old('username') }}" required maxlength="50">
                @error('username') <span class="error">{{ $message }}</span> @enderror
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="email">Email <span class="required">*</span></label>
                <input type="email" id="email" name="email" value="{{ old('email') }}" required maxlength="100">
                @error('email') <span class="error">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
                <label for="role">Role <span class="required">*</span></label>
                <select id="role" name="role" required>
                    <option value="">Select Role</option>
                    <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>Admin</option>
                    <option value="coach" {{ old('role') === 'coach' ? 'selected' : '' }}>Coach</option>
                    <option value="client" {{ old('role') === 'client' ? 'selected' : '' }}>Client</option>
                </select>
                @error('role') <span class="error">{{ $message }}</span> @enderror
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="password">Password <span class="required">*</span></label>
                <input type="password" id="password" name="password" required minlength="8">
                @error('password') <span class="error">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
                <label for="password_confirmation">Confirm Password <span class="required">*</span></label>
                <input type="password" id="password_confirmation" name="password_confirmation" required minlength="8">
            </div>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Create User</button>
            <a href="{{ route('users.index') }}" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>
@endsection
