@extends('layouts.app')

@section('title', 'Client Check In')

@section('content')
<div class="page-header">
    <h1>Client Check In List</h1>
</div>

<div class="checkin-container">

    <div class="table-container">
        <table class="checkin-table">
            <thead>
                <tr>
                    <th>Client Name</th>
                    <th>Loom Link</th>
                    <th>Package</th>
                    <th>Check In Frequency</th>
                    <th>When</th>
                    <th>Submitted</th>
                    <th>Rank</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @if($clients->isEmpty())
                    <tr>
                        <td colspan="8" style="text-align: center; padding: 2rem; color: var(--secondary-color);">
                            No clients found. <a href="{{ route('clients.create') }}">Add a client</a> to get started.
                        </td>
                    </tr>
                @else
                    @foreach($clients as $client)
                        <tr>
                            <form method="POST" action="{{ route('checkin.update', $client) }}" class="checkin-form" id="form-{{ $client->id }}">
                                @csrf
                                @method('PATCH')
                                <td class="client-name-cell">{{ $client->full_name }}</td>
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
                                        <option value="">Select Frequency</option>
                                        <option value="Weekly" {{ ($client->check_in_frequency ?? '') == 'Weekly' ? 'selected' : '' }}>Weekly</option>
                                        <option value="Fortnightly" {{ ($client->check_in_frequency ?? '') == 'Fortnightly' ? 'selected' : '' }}>Fortnightly</option>
                                        <option value="Monthly" {{ ($client->check_in_frequency ?? '') == 'Monthly' ? 'selected' : '' }}>Monthly</option>
                                    </select>
                                </td>
                                <td class="day-cell">
                                    <select name="check_in_day">
                                        <option value="">Select Day</option>
                                        <option value="Monday" {{ ($client->check_in_day ?? '') == 'Monday' ? 'selected' : '' }}>Monday</option>
                                        <option value="Tuesday" {{ ($client->check_in_day ?? '') == 'Tuesday' ? 'selected' : '' }}>Tuesday</option>
                                        <option value="Wednesday" {{ ($client->check_in_day ?? '') == 'Wednesday' ? 'selected' : '' }}>Wednesday</option>
                                        <option value="Thursday" {{ ($client->check_in_day ?? '') == 'Thursday' ? 'selected' : '' }}>Thursday</option>
                                        <option value="Friday" {{ ($client->check_in_day ?? '') == 'Friday' ? 'selected' : '' }}>Friday</option>
                                        <option value="Saturday" {{ ($client->check_in_day ?? '') == 'Saturday' ? 'selected' : '' }}>Saturday</option>
                                        <option value="Sunday" {{ ($client->check_in_day ?? '') == 'Sunday' ? 'selected' : '' }}>Sunday</option>
                                    </select>
                                </td>
                                <td class="submitted-cell {{ ($client->submitted ?? '') == 'Submitted' ? 'submitted-active' : '' }}">
                                    <select name="submitted" class="submitted-select">
                                        <option value="">Not Submitted</option>
                                        <option value="Submitted" {{ ($client->submitted ?? '') == 'Submitted' ? 'selected' : '' }}>Submitted</option>
                                    </select>
                                </td>
                                <td class="rank-cell">
                                    <input type="text" name="rank" value="{{ $client->rank ?? '' }}" placeholder="e.g., 70-100%" style="width: 100px;">
                                </td>
                                <td class="actions-cell">
                                    <button type="submit" class="btn-save">Save</button>
                                </td>
                            </form>
                        </tr>
                    @endforeach
                @endif
            </tbody>
        </table>
    </div>

    {{ $clients->links() }}
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Highlight submitted cells with green background - matching Excel
    function updateSubmittedCells() {
        const submittedSelects = document.querySelectorAll('select[name="submitted"]');

        submittedSelects.forEach(select => {
            const cell = select.closest('td.submitted-cell');
            if (select.value === 'Submitted') {
                cell.classList.add('submitted-active');
            } else {
                cell.classList.remove('submitted-active');
            }
        });
    }

    // Update on page load
    updateSubmittedCells();

    // Update when selects change
    const submittedSelects = document.querySelectorAll('select[name="submitted"]');
    submittedSelects.forEach(select => {
        select.addEventListener('change', function() {
            const cell = this.closest('td.submitted-cell');
            if (this.value === 'Submitted') {
                cell.classList.add('submitted-active');
            } else {
                cell.classList.remove('submitted-active');
            }
        });
    });
});
</script>
@endpush
@endsection
