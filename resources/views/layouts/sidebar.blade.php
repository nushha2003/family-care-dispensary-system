@php
    $role = Auth::user()->role;
@endphp

<!-- Brand / Header -->
<li class="nav-item mb-3">
    <div class="text-muted small px-3 text-uppercase">
        <i class="bi bi-grid-1x2-fill"></i> Menu
    </div>
</li>

<!-- Dashboard (Common for all) -->
<li class="nav-item">
    <a class="nav-link {{ request()->routeIs('*.dashboard') ? 'active' : '' }}"
       href="{{ route($role . '.dashboard') }}">
        <i class="bi bi-speedometer2"></i> Dashboard
    </a>
</li>

<!-- PATIENTS (Common for all) -->
<li class="nav-item">
    <a class="nav-link {{ request()->routeIs('patients.*') ? 'active' : '' }}"
       href="{{ route('patients.index') }}">
        <i class="bi bi-person"></i> Patients
    </a>
</li>

<!-- APPOINTMENTS (Common for all) -->
<li class="nav-item">
    <a class="nav-link {{ request()->routeIs('appointments.*') ? 'active' : '' }}"
       href="{{ route('appointments.index') }}">
        <i class="bi bi-calendar-check"></i> Appointments
    </a>
</li>

@if($role === 'admin')
    <!-- === ADMIN SPECIFIC === -->
    <li class="nav-item mt-3">
        <div class="text-muted small px-3 text-uppercase">Management</div>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ request()->routeIs('medicines.*') ? 'active' : '' }}"
           href="{{ route('medicines.index') }}">
            <i class="bi bi-capsule"></i> Medicine Stock
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ request()->routeIs('prescriptions.*') ? 'active' : '' }}"
           href="{{ route('prescriptions.index') }}">
            <i class="bi bi-prescription"></i> All Prescriptions
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="#">
            <i class="bi bi-people"></i> Manage Users
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="#">
            <i class="bi bi-graph-up"></i> Reports
        </a>
    </li>
@endif

@if($role === 'doctor')
    <!-- === DOCTOR SPECIFIC === -->
    <li class="nav-item mt-3">
        <div class="text-muted small px-3 text-uppercase">Clinical</div>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ request()->routeIs('prescriptions.*') ? 'active' : '' }}"
           href="{{ route('prescriptions.index') }}">
            <i class="bi bi-prescription"></i> My Prescriptions
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="{{ route('appointments.index') }}">
            <i class="bi bi-clock-history"></i> Today's Schedule
        </a>
    </li>
@endif

@if($role === 'receptionist')
    <!-- === RECEPTIONIST SPECIFIC === -->
    <li class="nav-item mt-3">
        <div class="text-muted small px-3 text-uppercase">Front Desk</div>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ request()->routeIs('patients.create') ? 'active' : '' }}"
           href="{{ route('patients.create') }}">
            <i class="bi bi-person-plus"></i> Register Patient
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ request()->routeIs('appointments.create') ? 'active' : '' }}"
           href="{{ route('appointments.create') }}">
            <i class="bi bi-calendar-plus"></i> Book Appointment
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ request()->routeIs('prescriptions.*') ? 'active' : '' }}"
           href="{{ route('prescriptions.index') }}">
            <i class="bi bi-prescription"></i> Dispense / Prescriptions
        </a>
    </li>

    <li class="nav-item mt-3">
    <div class="text-muted small px-3 text-uppercase">Reports</div>
</li>
<li class="nav-item">
    <a class="nav-link {{ request()->routeIs('reports.daily') ? 'active' : '' }}"
       href="{{ route('reports.daily') }}">
        <i class="bi bi-calendar-day"></i> Daily Revenue
    </a>
</li>
<li class="nav-item">
    <a class="nav-link {{ request()->routeIs('reports.weekly') ? 'active' : '' }}"
       href="{{ route('reports.weekly') }}">
        <i class="bi bi-calendar-week"></i> Weekly Summary
    </a>
</li>
@endif

<!-- Logout (Optional extra) -->
<li class="nav-item mt-4">
    <hr class="border-secondary">
</li>
<li class="nav-item">
    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" class="nav-link text-danger" style="background:none; border:none; width:100%; text-align:left;">
            <i class="bi bi-box-arrow-right"></i> Logout
        </button>
    </form>
</li>