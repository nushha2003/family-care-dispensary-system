@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Book Appointment</h1>
        <a href="{{ route('appointments.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Back
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('appointments.store') }}" method="POST">
                @csrf
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="patient_id" class="form-label">Patient *</label>
                        <select name="patient_id" id="patient_id" class="form-control @error('patient_id') is-invalid @enderror" required>
                            <option value="">Select Patient</option>
                            @foreach($patients as $patient)
                            <option value="{{ $patient->id }}" {{ old('patient_id')==$patient->id?'selected':'' }}>
                                {{ $patient->full_name }} ({{ $patient->phone }})
                            </option>
                            @endforeach
                        </select>
                        @error('patient_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="doctor_id" class="form-label">Doctor *</label>
                        <select name="doctor_id" id="doctor_id" class="form-control @error('doctor_id') is-invalid @enderror" required>
                            <option value="">Select Doctor</option>
                            @foreach($doctors as $doctor)
                            <option value="{{ $doctor->id }}" {{ old('doctor_id')==$doctor->id?'selected':'' }}>
                                {{ $doctor->name }}
                            </option>
                            @endforeach
                        </select>
                        @error('doctor_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="appointment_date" class="form-label">Date *</label>
                        <input type="date" name="appointment_date" id="appointment_date"
                               class="form-control @error('appointment_date') is-invalid @enderror"
                               value="{{ old('appointment_date', date('Y-m-d')) }}" required>
                        @error('appointment_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="time_slot" class="form-label">Time *</label>
                        <input type="time" name="time_slot" id="time_slot"
                               class="form-control @error('time_slot') is-invalid @enderror"
                               value="{{ old('time_slot', '09:00') }}" required>
                        @error('time_slot')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-12 mb-3">
                        <label for="notes" class="form-label">Notes</label>
                        <textarea name="notes" id="notes" class="form-control" rows="3">{{ old('notes') }}</textarea>
                    </div>
                </div>
                <div class="mt-3">
                    <button type="submit" class="btn btn-success">
                        <i class="bi bi-check-circle"></i> Book Appointment
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection