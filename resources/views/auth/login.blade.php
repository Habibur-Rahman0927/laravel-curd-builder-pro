<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>Login - Laravel CRUD Builder</title>
    <meta name="description" content="Login to the Laravel CRUD Builder - the ultimate tool for automated CRUD operations with flexible customization options.">
    <meta name="keywords" content="Laravel, CRUD, Builder, Login, Authentication, Admin, Automated, Database">
    <meta name="robots" content="index, follow">
    
    <link href="{{ asset('assets/img/favicon.png') }}" rel="icon">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
    
    <link href="{{ asset('assets/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css" rel="stylesheet">
    <link href="{{ asset('assets/css/custom-style-login-register.css') }}" rel="stylesheet">
</head>

<body>
    <div class="container">
        <a href="#" class="logo">Laravel CRUD Builder</a>
        <div class="card">
            <h5 class="card-title">Login to Your Account</h5>
            <p class="text-center small mb-4">Enter your email & password to login</p>

            <form method="POST" action="{{ route('login') }}">
                @csrf
                <!-- Email Address -->
                <div class="form-group">
                    <i class="bi bi-envelope input-icon"></i>
                    <label for="email" class="visually-hidden">Email</label>
                    <input id="email" class="form-control" type="email" name="email" placeholder="Email" value="{{ old('email') }}" required autofocus autocomplete="username">
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Password -->
                <div class="form-group">
                    <i class="bi bi-lock input-icon"></i>
                    <label for="password" class="visually-hidden">Password</label>
                    <input id="password" class="form-control" type="password" name="password" placeholder="Password" required autocomplete="current-password">
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Remember Me -->
                <div class="form-check mb-3">
                    <input id="remember_me" type="checkbox" class="form-check-input" name="remember">
                    <label class="form-check-label" for="remember_me">Remember me</label>
                </div>

                <button type="submit" class="btn btn-primary">Log in</button>

                <div class="d-flex justify-content-between mt-3">
                    @if (Route::has('password.request'))
                        <a class="text-decoration-none" href="{{ route('password.request') }}">Forgot your password?</a>
                    @endif
                    <a class="text-decoration-none" href="{{ route('register') }}">Don't have an account?</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
