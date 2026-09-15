@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Reception Dashboard</h1>
        <span class="text-muted">{{ \Carbon\Carbon::now()->format('l, d M Y') }}</span>
    </div>

    @php
        use App\Models\Appointment;
        use App\Models\Patient;
        use App\Models\Prescription;

        $todayAppointments = Appointment::whereDate('appointment_date', today())->count();
        $todayCompleted = Appointment::whereDate('appointment_date', today())
            ->where('status', 'completed')
            ->count();
        $todayWaiting = Appointment::whereDate('appointment_date', today())
            ->where('status', 'waiting')
            ->count();
        $totalPatients = Patient::count();
        $pendingDispense = Prescription::where('is_dispensed', false)->count();
        $todayRegistered = Patient::whereDate('created_at', today())->count();
    @endphp

    <div class="row">
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card p-3 text-center bg-primary bg-opacity-10 border-primary">
                <h5>Today's Appointments</h5>
                <p class="display-4">{{ $todayAppointments }}</p>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card p-3 text-center bg-success bg-opacity-10 border-success">
                <h5>Completed</h5>
                <p class="display-4">{{ $todayCompleted }}</p>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card p-3 text-center bg-warning bg-opacity-10 border-warning">
                <h5>Waiting</h5>
                <p class="display-4">{{ $todayWaiting }}</p>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card p-3 text-center bg-info bg-opacity-10 border-info">
                <h5>Total Patients</h5>
                <p class="display-4">{{ $totalPatients }}</p>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6 mb-3">
            <div class="card p-3 text-center bg-danger bg-opacity-10 border-danger">
                <h5><i class="bi bi-prescription"></i> Pending Dispense</h5>
                <p class="display-4">{{ $pendingDispense }}</p>
                <a href="{{ route('prescriptions.index') }}" class="btn btn-danger">View Pending</a>
            </div>
        </div>
        <div class="col-md-6 mb-3">
            <div class="card p-3 text-center bg-secondary bg-opacity-10 border-secondary">
                <h5><i class="bi bi-person-plus"></i> New Patients Today</h5>
                <p class="display-4">{{ $todayRegistered }}</p>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12 mb-3">
            <div class="card p-3">
                <h5>Quick Actions</h5>
                <div class="row g-2">
                    <div class="col-md-4">
                        <a href="{{ route('patients.create') }}" class="btn btn-success w-100">
                            <i class="bi bi-person-plus"></i> Register Patient
                        </a>
                    </div>
                    <div class="col-md-4">
                        <a href="{{ route('appointments.create') }}" class="btn btn-primary w-100">
                            <i class="bi bi-calendar-plus"></i> Book Appointment
                        </a>
                    </div>
                    <div class="col-md-4">
                        <a href="{{ route('appointments.index') }}" class="btn btn-info w-100">
                            <i class="bi bi-calendar-check"></i> Check-in Patient
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection