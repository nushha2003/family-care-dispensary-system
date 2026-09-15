@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Doctor Dashboard</h1>
        <span class="text-muted">{{ \Carbon\Carbon::now()->format('l, d M Y') }}</span>
    </div>

    @php
        use App\Models\Appointment;
        use App\Models\Prescription;
        use App\Models\Patient;

        $doctorId = auth()->id();

        $todayAppointments = Appointment::where('doctor_id', $doctorId)
            ->whereDate('appointment_date', today())
            ->count();

        $todayCompleted = Appointment::where('doctor_id', $doctorId)
            ->whereDate('appointment_date', today())
            ->where('status', 'completed')
            ->count();

        $pendingPrescriptions = Prescription::where('doctor_id', $doctorId)
            ->where('is_dispensed', false)
            ->count();

        $waitingPatients = Appointment::where('doctor_id', $doctorId)
            ->whereDate('appointment_date', today())
            ->where('status', 'waiting')
            ->count();

        $myPatients = Patient::whereHas('appointments', function($q) use ($doctorId) {
            $q->where('doctor_id', $doctorId);
        })->distinct()->count();
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
                <p class="display-4">{{ $waitingPatients }}</p>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card p-3 text-center bg-info bg-opacity-10 border-info">
                <h5>Pending Prescriptions</h5>
                <p class="display-4">{{ $pendingPrescriptions }}</p>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6 mb-3">
            <div class="card p-3">
                <h5>My Patients</h5>
                <p class="display-6">{{ $myPatients }}</p>
                <a href="{{ route('patients.index') }}" class="btn btn-outline-primary">View All Patients</a>
            </div>
        </div>
        <div class="col-md-6 mb-3">
            <div class="card p-3">
                <h5>Quick Actions</h5>
                <div class="d-grid gap-2">
                    <a href="{{ route('appointments.index') }}" class="btn btn-primary">View Today's Schedule</a>
                    <a href="{{ route('prescriptions.create') }}" class="btn btn-success">Write New Prescription</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection