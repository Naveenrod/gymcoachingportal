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
    <nav class="navbar" role="navigation" aria-label="Main navigation">
        <div class="nav-brand">
            <h2>Building Her Coaching</h2>
        </div>
        <ul class="nav-menu">
            @auth
                @if(Auth::user()->role !== 'client')
                    <li><a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">Dashboard</a></li>
                    <li><a href="{{ route('clients.index') }}" class="{{ request()->routeIs('clients.*') ? 'active' : '' }}">Clients</a></li>
                    <li><a href="{{ route('appointments.index') }}" class="{{ request()->routeIs('appointments.*') ? 'active' : '' }}">Appointments</a></li>
                    <li><a href="{{ route('checkin.index') }}" class="{{ request()->routeIs('checkin.*') ? 'active' : '' }}">Check In</a></li>
                    @if(Auth::user()->isAdmin())
                        <li><a href="{{ route('users.index') }}" class="{{ request()->routeIs('users.*') ? 'active' : '' }}">Users</a></li>
                    @endif
                @else
                    <li><a href="{{ route('portal.dashboard') }}" class="{{ request()->routeIs('portal.dashboard') ? 'active' : '' }}">My Dashboard</a></li>
                    <li><a href="{{ route('portal.appointments') }}" class="{{ request()->routeIs('portal.appointments') ? 'active' : '' }}">My Appointments</a></li>
                    <li><a href="{{ route('portal.profile') }}" class="{{ request()->routeIs('portal.profile') ? 'active' : '' }}">My Profile</a></li>
                @endif
            @endauth
        </ul>
        <div class="nav-user">
            <span>Welcome, {{ Auth::check() ? Auth::user()->full_name : '' }}</span>
            <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                @csrf
                <button type="submit" class="btn btn-sm btn-secondary">Logout</button>
            </form>
        </div>
    </nav>
    <div class="container">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible" role="alert">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-error alert-dismissible" role="alert">{{ session('error') }}</div>
        @endif
        @if(session('warning'))
            <div class="alert alert-warning alert-dismissible" role="alert">{{ session('warning') }}</div>
        @endif
        @if(session('info'))
            <div class="alert alert-info alert-dismissible" role="alert">{{ session('info') }}</div>
        @endif
        @yield('content')
    </div>
    <footer class="footer">
        <p>&copy; {{ date('Y') }} Building Her Coaching. All rights reserved.</p>
    </footer>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.alert-dismissible').forEach(function(alert) {
                setTimeout(function() {
                    alert.style.opacity = '0';
                    alert.style.transition = 'opacity 0.5s ease-out';
                    setTimeout(function() { alert.remove(); }, 500);
                }, 5000);
            });
        });
    </script>
    @stack('scripts')
</body>
</html>
