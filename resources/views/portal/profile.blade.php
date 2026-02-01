@extends('layouts.app')

@section('title', 'My Profile')

@section('content')
<div class="page-header">
    <h1>My Profile</h1>
</div>

<div class="detail-card">
    <div class="detail-grid">
        <div class="detail-item">
            <label>Name</label>
            <p>{{ $client->full_name }}</p>
        </div>
        <div class="detail-item">
            <label>Email</label>
            <p>{{ $client->email }}</p>
        </div>
        @if($client->phone)
            <div class="detail-item">
                <label>Phone</label>
                <p>{{ $client->phone }}</p>
            </div>
        @endif
        <div class="detail-item">
            <label>Membership Type</label>
            <p><span class="badge badge-{{ strtolower($client->membership_type) }}">{{ $client->membership_type }}</span></p>
        </div>
        <div class="detail-item">
            <label>Status</label>
            <p><span class="status status-{{ strtolower(str_replace(' ', '-', $client->status)) }}">{{ $client->status }}</span></p>
        </div>
        @if($client->membership_start_date)
            <div class="detail-item">
                <label>Membership Start</label>
                <p>{{ $client->membership_start_date->format('F j, Y') }}</p>
            </div>
        @endif
        @if($client->membership_end_date)
            <div class="detail-item">
                <label>Membership End</label>
                <p>{{ $client->membership_end_date->format('F j, Y') }}</p>
            </div>
        @endif
    </div>
</div>
@endsection
