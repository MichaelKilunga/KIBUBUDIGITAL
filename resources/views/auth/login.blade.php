<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('messages.login_title') }} - {{ $allSettings['site_name'] ?? 'Kibubu Digital' }}</title>
    <link rel="icon" type="image/png" href="{{ isset($allSettings['site_logo']) ? asset($allSettings['site_logo']) : asset('images/logo.png') }}">
    <link rel="apple-touch-icon" href="{{ isset($allSettings['site_logo']) ? asset($allSettings['site_logo']) : asset('images/logo.png') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-gold: #D4AF37;
            --bg-color: #f8f9fa;
            --text-color: #212529;
            --card-bg: #ffffff;
        }
        @media (prefers-color-scheme: dark) {
            :root {
                --bg-color: #121212;
                --text-color: #f8f9fa;
                --card-bg: #1e1e1e;
            }
        }
        body { background: var(--bg-color); color: var(--text-color); display: flex; align-items: center; justify-content: center; min-height: 100vh; transition: all 0.3s ease; }
        .login-card { width: 100%; max-width: 400px; border: none; border-radius: 15px; box-shadow: 0 10px 25px rgba(0,0,0,0.1); background: var(--card-bg); color: var(--text-color); }
        .btn-gold { background: var(--primary-gold); color: white; border: none; font-weight: 600; }
        .btn-gold:hover { background: #b8952d; color: white; }
        .form-control { background-color: var(--card-bg); color: var(--text-color); border-color: rgba(212,175,55,0.2); }
        .form-control:focus { background-color: var(--card-bg); color: var(--text-color); }
    </style>
</head>
<body>

<div class="card login-card p-4">
    <div class="text-center mb-4">
        <div class="mb-3">
            <img src="{{ isset($allSettings['site_logo']) ? asset($allSettings['site_logo']) : asset('images/logo.png') }}" 
                 alt="Logo" class="img-fluid" style="max-height: 60px;">
        </div>
        <h2 class="fw-bold">{{ $allSettings['site_name'] ?? 'Kibubu Admin' }}</h2>
        <p class="text-muted">{{ __('messages.login_subtitle') }}</p>
    </div>

    @if($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form action="{{ route('login.post') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="email" class="form-label">{{ __('messages.email') }}</label>
            <input type="email" name="email" class="form-control" id="email" value="{{ old('email') }}" required autofocus>
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">{{ __('messages.password') }}</label>
            <input type="password" name="password" class="form-control" id="password" required>
        </div>
        <div class="mb-3 form-check">
            <input type="checkbox" name="remember" class="form-check-input" id="remember">
            <label class="form-check-label" for="remember">{{ __('messages.remember_me') }}</label>
        </div>
        <button type="submit" class="btn btn-gold w-100 py-2">{{ __('messages.sign_in') }}</button>
    </form>
    
    <div class="text-center mt-3">
        <a href="{{ route('home') }}" class="text-muted small text-decoration-none">&larr; {{ __('messages.back_to_home') }}</a>
    </div>
</div>

</body>
</html>
