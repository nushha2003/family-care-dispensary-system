<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-bs-theme="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Dispensary System') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body>
    <div class="d-flex">
        <!-- Overlay for mobile -->
        <div class="overlay" id="sidebarOverlay"></div>

        <!-- Sidebar -->
        <nav class="sidebar" id="sidebar">
            <div class="brand">
                <i class="bi bi-heart-pulse-fill"></i> FamilyCare
            </div>
            <ul class="nav flex-column">
                @include('layouts.sidebar')
            </ul>
        </nav>

        <!-- Main Content -->
        <div class="main-content flex-grow-1">
            <!-- Top Navbar -->
            <nav class="navbar navbar-expand-md bg-transparent mb-4">
                <div class="container-fluid">
                    <button class="sidebar-toggle" id="sidebarToggle">
                        <i class="bi bi-list"></i>
                    </button>
                    <span class="navbar-brand d-md-none">FamilyCare</span>
                    <div class="ms-auto d-flex align-items-center">
                        <!-- Dark Mode Toggle -->
                        <button class="btn btn-outline-secondary me-3" id="darkModeToggle">
                            <i class="bi bi-moon-fill" id="darkModeIcon"></i>
                        </button>
                        <!-- User Dropdown -->
                        <div class="dropdown">
                            <button class="btn btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown">
                                {{ Auth::user()->name }}
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="{{ route('profile.edit') }}">Profile</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button class="dropdown-item" type="submit">Logout</button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </nav>

            <!-- Page Content -->
            @yield('content')
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Sidebar toggle
        const toggleBtn = document.getElementById('sidebarToggle');
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('sidebarOverlay');

        function toggleSidebar() {
            sidebar.classList.toggle('open');
            overlay.classList.toggle('show');
        }
        toggleBtn.addEventListener('click', toggleSidebar);
        overlay.addEventListener('click', toggleSidebar);

        // Dark Mode
        const toggleDark = document.getElementById('darkModeToggle');
        const icon = document.getElementById('darkModeIcon');
        const html = document.documentElement;

        function setTheme(theme) {
            html.setAttribute('data-bs-theme', theme);
            icon.className = theme === 'dark' ? 'bi bi-sun-fill' : 'bi bi-moon-fill';
            localStorage.setItem('theme', theme);
        }

        // Load saved theme
        const savedTheme = localStorage.getItem('theme') || 'light';
        setTheme(savedTheme);

        toggleDark.addEventListener('click', () => {
            const current = html.getAttribute('data-bs-theme');
            setTheme(current === 'dark' ? 'light' : 'dark');
        });
    </script>
    @stack('scripts')
</body>
</html>