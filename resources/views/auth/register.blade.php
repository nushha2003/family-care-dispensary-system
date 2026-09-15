<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-bs-theme="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'MediCare') }} - Register</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        :root {
            --primary-green: #2e7d32;
            --primary-green-light: #4caf50;
            --dark-bg: #0d0d0d;
        }
        body {
            background: linear-gradient(135deg, var(--dark-bg) 0%, #1a1a1a 50%, var(--primary-green) 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            padding: 20px;
        }
        .register-container {
            background: white;
            border-radius: 20px;
            padding: 40px;
            max-width: 500px;
            width: 100%;
            box-shadow: 0 20px 60px rgba(0,0,0,0.5);
            transition: background-color 0.3s, color 0.3s;
        }
        [data-bs-theme="dark"] .register-container {
            background: #1a1a1a;
            color: #f0f0f0;
        }
        [data-bs-theme="dark"] .register-container .form-control {
            background: #2d2d2d;
            color: #f0f0f0;
            border-color: #444;
        }
        [data-bs-theme="dark"] .register-container .form-control:focus {
            background: #333;
            border-color: var(--primary-green-light);
        }
        [data-bs-theme="dark"] .register-container .text-muted {
            color: #aaa !important;
        }
        .register-container .brand {
            text-align: center;
            margin-bottom: 30px;
        }
        .register-container .brand i {
            font-size: 3rem;
            color: var(--primary-green-light);
        }
        .register-container .brand h3 {
            font-weight: 700;
            color: var(--primary-green);
            margin-top: 10px;
        }
        [data-bs-theme="dark"] .register-container .brand h3 {
            color: var(--primary-green-light);
        }
        .btn-green {
            background: var(--primary-green);
            color: white;
            border: none;
            padding: 12px;
            font-weight: 600;
            border-radius: 10px;
            transition: all 0.3s;
            width: 100%;
        }
        .btn-green:hover {
            background: var(--primary-green-light);
            transform: translateY(-2px);
            box-shadow: 0 10px 30px rgba(76, 175, 80, 0.4);
        }
        .theme-toggle {
            position: fixed;
            top: 20px;
            right: 20px;
            background: rgba(255,255,255,0.2);
            border: none;
            color: white;
            font-size: 1.5rem;
            padding: 10px 15px;
            border-radius: 50%;
            cursor: pointer;
            backdrop-filter: blur(10px);
            transition: all 0.3s;
        }
        .theme-toggle:hover {
            background: rgba(255,255,255,0.3);
            transform: scale(1.1);
        }
        [data-bs-theme="dark"] .theme-toggle {
            background: rgba(0,0,0,0.3);
        }
    </style>
</head>
<body>

    <!-- Theme Toggle -->
    <button class="theme-toggle" id="themeToggle">
        <i class="bi bi-moon-fill" id="themeIcon"></i>
    </button>

    <div class="register-container">
        <div class="brand">
            <i class="bi bi-heart-pulse-fill"></i>
            <h3>FamilyCare</h3>
            <p class="text-muted">Create your account</p>
        </div>

        <form method="POST" action="{{ route('register') }}">
            @csrf

            <!-- Name -->
            <div class="mb-3">
                <label for="name" class="form-label">Full Name</label>
                <input type="text" class="form-control @error('name') is-invalid @enderror"
                       id="name" name="name" value="{{ old('name') }}" required autofocus>
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Email -->
            <div class="mb-3">
                <label for="email" class="form-label">Email Address</label>
                <input type="email" class="form-control @error('email') is-invalid @enderror"
                       id="email" name="email" value="{{ old('email') }}" required>
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Password -->
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control @error('password') is-invalid @enderror"
                       id="password" name="password" required>
                @error('password')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Confirm Password -->
            <div class="mb-3">
                <label for="password_confirmation" class="form-label">Confirm Password</label>
                <input type="password" class="form-control"
                       id="password_confirmation" name="password_confirmation" required>
            </div>

            <button type="submit" class="btn-green">Register</button>

            <div class="text-center mt-3">
                <span class="text-muted">Already have an account?</span>
                <a href="{{ route('login') }}" class="text-decoration-none" style="color: var(--primary-green); font-weight: 600;">
                    Log In
                </a>
            </div>
        </form>
    </div>

    <script>
        // Dark Mode Toggle
        const toggle = document.getElementById('themeToggle');
        const icon = document.getElementById('themeIcon');
        const html = document.documentElement;

        function setTheme(theme) {
            html.setAttribute('data-bs-theme', theme);
            icon.className = theme === 'dark' ? 'bi bi-sun-fill' : 'bi bi-moon-fill';
            localStorage.setItem('theme', theme);
        }

        const saved = localStorage.getItem('theme') || 'light';
        setTheme(saved);

        toggle.addEventListener('click', () => {
            const current = html.getAttribute('data-bs-theme');
            setTheme(current === 'dark' ? 'light' : 'dark');
        });
    </script>
</body>
</html>