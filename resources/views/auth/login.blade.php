<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Building Her Coaching</title>
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
</head>
<body class="login-page">
    <div class="login-container">
        <div class="login-box">
            <h1>Building Her Coaching</h1>
            <h2>Login</h2>

            @if($errors->any())
                <div class="alert alert-error">
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" value="{{ old('username') }}" required autofocus>
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required>
                </div>

                <button type="submit" class="btn btn-primary btn-block">Login</button>
            </form>
        </div>

        @if(app()->environment('local') && isset($devUsers) && $devUsers->count())
            <div class="dev-login-panel">
                <div class="dev-login-header">
                    <span class="dev-login-icon">⚡</span>
                    <h3>Quick Login <span class="dev-mode-badge">DEV MODE</span></h3>
                    <p>Click any user below to instantly log in</p>
                </div>
                <div class="dev-login-grid">
                    @foreach($devUsers as $devUser)
                        <form method="POST" action="{{ route('dev.login') }}" class="dev-login-form">
                            @csrf
                            <input type="hidden" name="user_id" value="{{ $devUser->id }}">
                            <button type="submit" class="dev-user-btn">
                                <div class="dev-user-info">
                                    <span class="dev-user-name">{{ $devUser->full_name }}</span>
                                    <span class="dev-user-username">@ {{ $devUser->username }}</span>
                                </div>
                                <span class="role-badge role-badge-{{ $devUser->role }}">{{ ucfirst($devUser->role) }}</span>
                            </button>
                        </form>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</body>
</html>
