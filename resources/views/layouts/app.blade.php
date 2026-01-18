<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Gym Coaching Portal')</title>
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    @stack('styles')
</head>
<body>
    <nav class="navbar">
        <div class="nav-brand">
            <h2>Building Her Coaching</h2>
        </div>
        <ul class="nav-menu">
            <li><a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">Dashboard</a></li>
            <li><a href="{{ route('clients.index') }}" class="{{ request()->routeIs('clients.*') ? 'active' : '' }}">Clients</a></li>
            <li><a href="{{ route('checkin.index') }}" class="{{ request()->routeIs('checkin.*') ? 'active' : '' }}">Check In</a></li>
        </ul>
        <div class="nav-user">
            <span>Welcome, {{ session('full_name') }}</span>
            <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                @csrf
                <button type="submit" class="btn btn-sm btn-secondary">Logout</button>
            </form>
        </div>
    </nav>
    <div class="container">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-error">{{ session('error') }}</div>
        @endif
        @yield('content')
    </div>
    <footer class="footer">
        <p>&copy; {{ date('Y') }} Gym Coaching Portal. All rights reserved.</p>
    </footer>
    @stack('scripts')
</body>
</html>
