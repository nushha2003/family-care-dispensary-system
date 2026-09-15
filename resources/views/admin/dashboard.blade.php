@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Admin Dashboard</h1>
        <span class="text-muted">{{ \Carbon\Carbon::now()->format('l, d M Y') }}</span>
    </div>

    @php
        use App\Models\Patient;
        use App\Models\Medicine;
        use App\Models\Appointment;
        use App\Models\Prescription;

        $totalPatients = Patient::count();
        $totalMedicines = Medicine::count();
        $todayAppointments = Appointment::whereDate('appointment_date', today())->count();
        $pendingPrescriptions = Prescription::where('is_dispensed', false)->count();
        $lowStock = Medicine::whereRaw('current_stock <= reorder_level')->count();
        $expired = Medicine::where('expiry_date', '<', today())->count();
        $outOfStock = Medicine::where('current_stock', 0)->count();
    @endphp

    <div class="row">
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card p-3 text-center bg-primary bg-opacity-10 border-primary">
                <h5>Total Patients</h5>
                <p class="display-4">{{ $totalPatients }}</p>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card p-3 text-center bg-success bg-opacity-10 border-success">
                <h5>Medicines</h5>
                <p class="display-4">{{ $totalMedicines }}</p>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card p-3 text-center bg-info bg-opacity-10 border-info">
                <h5>Today's Appointments</h5>
                <p class="display-4">{{ $todayAppointments }}</p>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card p-3 text-center bg-warning bg-opacity-10 border-warning">
                <h5>Pending Prescriptions</h5>
                <p class="display-4">{{ $pendingPrescriptions }}</p>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4 mb-3">
            <div class="card p-3 text-center bg-danger bg-opacity-10 border-danger">
                <h5><i class="bi bi-exclamation-triangle-fill"></i> Out of Stock</h5>
                <p class="display-4">{{ $outOfStock }}</p>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card p-3 text-center bg-warning bg-opacity-10 border-warning">
                <h5><i class="bi bi-arrow-down-circle"></i> Low Stock</h5>
                <p class="display-4">{{ $lowStock }}</p>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card p-3 text-center bg-secondary bg-opacity-10 border-secondary">
                <h5><i class="bi bi-calendar-x"></i> Expired</h5>
                <p class="display-4">{{ $expired }}</p>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-md-6 mb-3">
            <div class="card p-3">
                <h5>Quick Actions</h5>
                <div class="d-grid gap-2">
                    <a href="{{ route('patients.create') }}" class="btn btn-success">Register New Patient</a>
                    <a href="{{ route('medicines.create') }}" class="btn btn-primary">Add New Medicine</a>
                    <a href="{{ route('appointments.create') }}" class="btn btn-info">Book Appointment</a>
                </div>
            </div>
        </div>
        <div class="col-md-6 mb-3">
            <div class="card p-3">
                <h5>System Overview</h5>
                <ul class="list-group">
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        Total Medicines
                        <span class="badge bg-primary rounded-pill">{{ $totalMedicines }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        Total Patients
                        <span class="badge bg-success rounded-pill">{{ $totalPatients }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        Today's Appointments
                        <span class="badge bg-info rounded-pill">{{ $todayAppointments }}</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection