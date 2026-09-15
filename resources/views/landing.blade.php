<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-bs-theme="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'MediCare Dispensary') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        :root {
            --primary-green: #2e7d32;
            --primary-green-light: #4caf50;
            --dark-bg: #0d0d0d;
        }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f8f9fa;
        }
        .hero-section {
            background: linear-gradient(135deg, var(--dark-bg) 0%, #1a1a1a 50%, var(--primary-green) 100%);
            color: white;
            padding: 120px 0 80px;
            position: relative;
            overflow: hidden;
        }
        .hero-section::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -20%;
            width: 600px;
            height: 600px;
            background: rgba(76, 175, 80, 0.1);
            border-radius: 50%;
        }
        .hero-section h1 {
            font-size: 4rem;
            font-weight: 800;
            margin-bottom: 20px;
        }
        .hero-section h1 span {
            color: var(--primary-green-light);
        }
        .hero-section .lead {
            font-size: 1.3rem;
            opacity: 0.9;
            max-width: 600px;
        }
        .btn-success-custom {
            background: var(--primary-green-light);
            border: none;
            padding: 12px 40px;
            font-weight: 600;
            border-radius: 50px;
            transition: all 0.3s;
        }
        .btn-success-custom:hover {
            background: var(--primary-green);
            transform: translateY(-2px);
            box-shadow: 0 10px 30px rgba(76, 175, 80, 0.4);
        }
        .btn-outline-custom {
            border: 2px solid white;
            color: white;
            padding: 12px 40px;
            font-weight: 600;
            border-radius: 50px;
            transition: all 0.3s;
        }
        .btn-outline-custom:hover {
            background: white;
            color: var(--dark-bg);
            transform: translateY(-2px);
        }
        .feature-box {
            background: white;
            padding: 30px;
            border-radius: 16px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.06);
            transition: all 0.3s;
            height: 100%;
            border: 1px solid #e9ecef;
        }
        .feature-box:hover {
            transform: translateY(-8px);
            box-shadow: 0 12px 40px rgba(0,0,0,0.12);
            border-color: var(--primary-green-light);
        }
        .feature-box i {
            font-size: 2.5rem;
            color: var(--primary-green);
            margin-bottom: 15px;
        }
        .feature-box h5 {
            font-weight: 700;
        }
        .stats-section {
            background: var(--dark-bg);
            color: white;
            padding: 60px 0;
        }
        .stats-section .stat-number {
            font-size: 3rem;
            font-weight: 800;
            color: var(--primary-green-light);
        }
        .footer {
            background: #0d0d0d;
            color: rgba(255,255,255,0.7);
            padding: 40px 0;
        }
        .footer a {
            color: var(--primary-green-light);
            text-decoration: none;
        }
        .footer a:hover {
            color: white;
        }
        @media (max-width: 768px) {
            .hero-section h1 {
                font-size: 2.5rem;
            }
            .hero-section {
                padding: 80px 0 60px;
            }
        }
        .navbar-custom {
            background: rgba(13, 13, 13, 0.95) !important;
            backdrop-filter: blur(10px);
        }
        .navbar-custom .navbar-brand {
            color: var(--primary-green-light);
            font-weight: 800;
            font-size: 1.5rem;
        }
        .navbar-custom .nav-link {
            color: rgba(255,255,255,0.8) !important;
            font-weight: 500;
        }
        .navbar-custom .nav-link:hover {
            color: var(--primary-green-light) !important;
        }
    </style>
</head>
<body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-custom fixed-top">
        <div class="container">
            <a class="navbar-brand" href="#">
                <i class="bi bi-heart-pulse-fill"></i> FamilyCare
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="#features">Features</a></li>
                    <li class="nav-item"><a class="nav-link" href="#about">About</a></li>
                    @if (Route::has('login'))
                        @auth
                            <li class="nav-item">
                                <a class="nav-link" href="#">Dashboard</a>
                            </li>
                        @else
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('login') }}">Login</a>
                            </li>
                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">Register</a>
                                </li>
                            @endif
                        @endauth
                    @endif
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-section" id="home">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <h1>Smart <span>Dispensary</span> Management</h1>
                    <p class="lead">
                        Streamline your pharmacy operations with our all-in-one system.
                        Manage patients, prescriptions, inventory, and billing effortlessly.
                    </p>
                    <div class="mt-4">
                        @auth
                            <a href="{{ url('/dashboard') }}" class="btn btn-success-custom me-3">
                                <i class="bi bi-speedometer2"></i> Go to Dashboard
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="btn btn-success-custom me-3">
                                <i class="bi bi-box-arrow-in-right"></i> Get Started
                            </a>
                            <a href="{{ route('register') }}" class="btn btn-outline-custom">
                                Register
                            </a>
                        @endauth
                    </div>
                </div>
                <div class="col-lg-6 text-center d-none d-lg-block">
                    <i class="bi bi-heart-pulse-fill" style="font-size: 10rem; color: rgba(255,255,255,0.2);"></i>
                </div>
            </div>
        </div>
    </section>

    <!-- Features -->
    <section class="py-5" id="features">
        <div class="container">
            <div class="text-center mb-5">
                <h2 style="color: var(--primary-green); font-weight: 700;">Key Features</h2>
                <p class="text-muted">Everything you need to run a modern dispensary</p>
            </div>
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="feature-box">
                        <i class="bi bi-people"></i>
                        <h5>Patient Management</h5>
                        <p class="text-muted">Register patients, maintain medical history, track allergies, and manage appointments.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="feature-box">
                        <i class="bi bi-capsule"></i>
                        <h5>Medicine Inventory</h5>
                        <p class="text-muted">Track stock levels, expiry dates, get low-stock alerts, and manage suppliers.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="feature-box">
                        <i class="bi bi-prescription"></i>
                        <h5>Prescription Management</h5>
                        <p class="text-muted">Create digital prescriptions with allergy alerts, and track dispense status.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="feature-box">
                        <i class="bi bi-calendar-check"></i>
                        <h5>Appointment Scheduling</h5>
                        <p class="text-muted">Book appointments, check-in patients, and manage doctor schedules.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="feature-box">
                        <i class="bi bi-cash"></i>
                        <h5>Billing & Dispensing</h5>
                        <p class="text-muted">Generate invoices, print receipts, and auto-deduct stock on dispensing.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="feature-box">
                        <i class="bi bi-graph-up"></i>
                        <h5>Reports & Analytics</h5>
                        <p class="text-muted">Track revenue, view trends, and export reports for business insights.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats -->
    <section class="stats-section">
        <div class="container">
            <div class="row text-center g-4">
                <div class="col-md-3">
                    <div class="stat-number">500+</div>
                    <p>Patients Managed</p>
                </div>
                <div class="col-md-3">
                    <div class="stat-number">1000+</div>
                    <p>Prescriptions Processed</p>
                </div>
                <div class="col-md-3">
                    <div class="stat-number">50+</div>
                    <p>Medicines in Stock</p>
                </div>
                <div class="col-md-3">
                    <div class="stat-number">99%</div>
                    <p>Patient Satisfaction</p>
                </div>
            </div>
        </div>
    </section>

    <!-- About -->
    <section class="py-5" id="about">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <h2 style="color: var(--primary-green); font-weight: 700;">About FamilyCare</h2>
                    <p class="lead">Empowering healthcare providers with smart technology.</p>
                    <p>
                        MediCare Dispensary Management System is designed to simplify pharmacy operations.
                        From patient registration to medicine dispensing, our platform provides
                        a seamless experience for administrators, doctors, and receptionists.
                    </p>
                    <ul class="list-unstyled">
                        <li><i class="bi bi-check-circle-fill text-success"></i> Secure & Reliable</li>
                        <li><i class="bi bi-check-circle-fill text-success"></i> User-Friendly Interface</li>
                        <li><i class="bi bi-check-circle-fill text-success"></i> Real-Time Inventory Tracking</li>
                        <li><i class="bi bi-check-circle-fill text-success"></i> Comprehensive Reporting</li>
                    </ul>
                </div>
                <div class="col-lg-6 text-center">
                    <i class="bi bi-hospital" style="font-size: 12rem; color: var(--primary-green-light); opacity: 0.3;"></i>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <h5><i class="bi bi-heart-pulse-fill" style="color: var(--primary-green-light);"></i> FamilyCare</h5>
                    <p class="text-muted">Smart Dispensary Management System</p>
                </div>
                <div class="col-md-3">
                    <h6>Quick Links</h6>
                    <ul class="list-unstyled">
                        <li><a href="#features">Features</a></li>
                        <li><a href="#about">About</a></li>
                        @auth
                            <li><a href="{{ url('/dashboard') }}">Dashboard</a></li>
                        @else
                            <li><a href="{{ route('login') }}">Login</a></li>
                        @endauth
                    </ul>
                </div>
                <div class="col-md-3">
                    <h6>Contact</h6>
                    <ul class="list-unstyled">
                        <li><i class="bi bi-envelope"></i> info@Familycare.com</li>
                        <li><i class="bi bi-phone"></i> +1 234 567 890</li>
                    </ul>
                </div>
            </div>
            <hr class="border-secondary">
            <div class="text-center">
                <small>&copy; {{ date('Y') }} FamilyCare. All rights reserved.</small>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>