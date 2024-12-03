<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>Register - Laravel CRUD Builder</title>
    <meta name="description" content="Register to the Laravel CRUD Builder - create your account to start building CRUD applications effortlessly.">
    <meta name="keywords" content="Laravel, CRUD, Builder, Registration, Account, Automated, Database">
    <meta name="robots" content="index, follow">

    <link href="{{ asset('assets/img/favicon.png') }}" rel="icon">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
    <link href="{{ asset('assets/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <!-- Correct link for Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css" rel="stylesheet">
    <link href="{{ asset('assets/css/custom-style-login-register.css') }}" rel="stylesheet">

</head>

<body>
    <div class="container">
        <a href="#" class="logo">Laravel CRUD Builder</a>
        <div class="card">
            <h5 class="card-title">Create Your Account</h5>
            <p class="text-center small mb-4">Enter your details to register</p>

            <form method="POST" action="{{ route('register') }}">
                @csrf

                <!-- Name -->
                <div class="form-group">
                    <i class="bi bi-person input-icon"></i>
                    <label for="name" class="visually-hidden">Name</label>
                    <input id="name" class="form-control" type="text" name="name" placeholder="Name" value="{{ old('name') }}" required autofocus autocomplete="name">
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Email Address -->
                <div class="form-group">
                    <i class="bi bi-envelope input-icon"></i>
                    <label for="email" class="visually-hidden">Email</label>
                    <input id="email" class="form-control" type="email" name="email" placeholder="Email" value="{{ old('email') }}" required autocomplete="username">
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Password -->
                <div class="form-group">
                    <i class="bi bi-lock input-icon"></i>
                    <label for="password" class="visually-hidden">Password</label>
                    <input id="password" class="form-control" type="password" name="password" placeholder="Password" required autocomplete="new-password">
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Confirm Password -->
                <div class="form-group">
                    <i class="bi bi-lock-fill input-icon"></i>
                    <label for="password_confirmation" class="visually-hidden">Confirm Password</label>
                    <input id="password_confirmation" class="form-control" type="password" name="password_confirmation" placeholder="Confirm Password" required autocomplete="new-password">
                    @error('password_confirmation')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <input type="hidden" name="role_id" value="1">

                <button type="submit" class="btn btn-primary">Register</button>
                <div class="d-flex justify-content-end">
                    <a class="text-decoration-none mt-2" href="{{ route('login') }}">Already registered?</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
