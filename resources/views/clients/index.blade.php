@extends('layouts.app')

@section('title', 'Clients')

@section('content')
<div class="page-header">
    <h1>Clients</h1>
    <a href="{{ route('clients.create') }}" class="btn btn-primary">Add New Client</a>
</div>

<div class="stats-row">
    <div class="stat-card">
        <h3>{{ $clients->total() }}</h3>
        <p>Total Clients</p>
    </div>
</div>

<div class="filters">
    <form method="GET" action="" class="filter-form">
        <input type="text" name="search" placeholder="Search clients..." value="{{ request('search') }}">
        <select name="status">
            <option value="">All Status</option>
            <option value="Active" {{ request('status') == 'Active' ? 'selected' : '' }}>Active</option>
            <option value="Inactive" {{ request('status') == 'Inactive' ? 'selected' : '' }}>Inactive</option>
            <option value="On Hold" {{ request('status') == 'On Hold' ? 'selected' : '' }}>On Hold</option>
        </select>
        <button type="submit" class="btn btn-secondary">Filter</button>
        @if(request('search') || request('status'))
            <a href="{{ route('clients.index') }}" class="btn btn-secondary">Clear</a>
        @endif
    </form>
</div>

<div class="table-container">
    <table>
        <thead>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Membership</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @if($clients->isEmpty())
                <tr>
                    <td colspan="6" style="text-align: center;">No clients found</td>
                </tr>
            @else
                @foreach($clients as $client)
                    <tr>
                        <td>{{ $client->full_name }}</td>
                        <td>{{ $client->email }}</td>
                        <td>{{ $client->phone ?: '-' }}</td>
                        <td><span class="badge badge-{{ strtolower($client->membership_type) }}">{{ $client->membership_type }}</span></td>
                        <td><span class="status status-{{ strtolower(str_replace(' ', '-', $client->status)) }}">{{ $client->status }}</span></td>
                        <td class="actions">
                            <a href="{{ route('clients.show', $client) }}" class="btn btn-sm btn-info">View</a>
                            <a href="{{ route('clients.edit', $client) }}" class="btn btn-sm btn-secondary">Edit</a>
                            <form method="POST" action="{{ route('clients.destroy', $client) }}" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this client?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            @endif
        </tbody>
    </table>
</div>

{{ $clients->appends(request()->query())->links() }}
@endsection
