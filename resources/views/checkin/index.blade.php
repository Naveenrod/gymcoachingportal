@extends('layouts.app')

@section('title', 'Client Check In')

@section('content')
<div class="page-header">
    <h1>Client Check In List</h1>
    <p>{{ $clients->total() }} {{ Str::plural('client', $clients->total()) }} total</p>
</div>

<div class="checkin-container">
    @if(session('success'))
        <div class="alert alert-success" style="margin: 1rem 1rem 0;">{{ session('success') }}</div>
    @endif

    <div class="table-responsive">
        <table class="checkin-table">
            <thead>
                <tr>
                    <th>Client</th>
                    <th>Loom Link</th>
                    <th>Package</th>
                    <th>Frequency</th>
                    <th>Day</th>
                    <th>Status</th>
                    <th>Rank</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse($clients as $client)
                    <tr>
                        <form method="POST" action="{{ route('checkin.update', $client) }}" class="checkin-form" id="form-{{ $client->id }}">
                            @csrf
                            @method('PATCH')
                            <td class="client-name-cell">
                                <a href="{{ route('clients.show', $client) }}">{{ $client->full_name }}</a>
                            </td>
                            <td class="loom-link-cell">
                                <input type="url" name="loom_link" value="{{ $client->loom_link ?? '' }}" placeholder="https://loom.com/...">
                            </td>
                            <td class="package-cell">
                                <select name="package">
                                    <option value="">Select Package</option>
                                    <option value="Macros & Program" {{ ($client->package ?? '') == 'Macros & Program' ? 'selected' : '' }}>Macros & Program</option>
                                    <option value="Meal Plan & Prog" {{ ($client->package ?? '') == 'Meal Plan & Prog' ? 'selected' : '' }}>Meal Plan & Prog</option>
                                    <option value="Ambassador" {{ ($client->package ?? '') == 'Ambassador' ? 'selected' : '' }}>Ambassador</option>
                                    <option value="Program Only" {{ ($client->package ?? '') == 'Program Only' ? 'selected' : '' }}>Program Only</option>
                                </select>
                            </td>
                            <td class="frequency-cell">
                                <select name="check_in_frequency">
                                    <option value="">Select</option>
                                    <option value="Weekly" {{ ($client->check_in_frequency ?? '') == 'Weekly' ? 'selected' : '' }}>Weekly</option>
                                    <option value="Fortnightly" {{ ($client->check_in_frequency ?? '') == 'Fortnightly' ? 'selected' : '' }}>Fortnightly</option>
                                    <option value="Monthly" {{ ($client->check_in_frequency ?? '') == 'Monthly' ? 'selected' : '' }}>Monthly</option>
                                </select>
                            </td>
                            <td class="day-cell">
                                <select name="check_in_day">
                                    <option value="">Select</option>
                                    <option value="Monday" {{ ($client->check_in_day ?? '') == 'Monday' ? 'selected' : '' }}>Mon</option>
                                    <option value="Tuesday" {{ ($client->check_in_day ?? '') == 'Tuesday' ? 'selected' : '' }}>Tue</option>
                                    <option value="Wednesday" {{ ($client->check_in_day ?? '') == 'Wednesday' ? 'selected' : '' }}>Wed</option>
                                    <option value="Thursday" {{ ($client->check_in_day ?? '') == 'Thursday' ? 'selected' : '' }}>Thu</option>
                                    <option value="Friday" {{ ($client->check_in_day ?? '') == 'Friday' ? 'selected' : '' }}>Fri</option>
                                    <option value="Saturday" {{ ($client->check_in_day ?? '') == 'Saturday' ? 'selected' : '' }}>Sat</option>
                                    <option value="Sunday" {{ ($client->check_in_day ?? '') == 'Sunday' ? 'selected' : '' }}>Sun</option>
                                </select>
                            </td>
                            <td class="submitted-cell {{ ($client->submitted ?? '') == 'Submitted' ? 'submitted-active' : '' }}">
                                <select name="submitted" class="submitted-select">
                                    <option value="">Not Submitted</option>
                                    <option value="Submitted" {{ ($client->submitted ?? '') == 'Submitted' ? 'selected' : '' }}>Submitted</option>
                                </select>
                            </td>
                            <td class="rank-cell">
                                <input type="text" name="rank" value="{{ $client->rank ?? '' }}" placeholder="e.g., Gold">
                            </td>
                            <td class="actions-cell">
                                <button type="submit" class="btn-save">Save</button>
                            </td>
                        </form>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="empty-message">
                            No clients found. <a href="{{ route('clients.create') }}">Add a client</a> to get started.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($clients->hasPages())
        <div style="padding: 1rem 1.5rem; border-top: 1px solid var(--border-color);">
            {{ $clients->links() }}
        </div>
    @endif
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    function updateSubmittedCells() {
        document.querySelectorAll('select[name="submitted"]').forEach(select => {
            const cell = select.closest('td.submitted-cell');
            if (select.value === 'Submitted') {
                cell.classList.add('submitted-active');
            } else {
                cell.classList.remove('submitted-active');
            }
        });
    }

    updateSubmittedCells();

    document.querySelectorAll('select[name="submitted"]').forEach(select => {
        select.addEventListener('change', function() {
            const cell = this.closest('td.submitted-cell');
            cell.classList.toggle('submitted-active', this.value === 'Submitted');
        });
    });
});
</script>
@endpush
@endsection
