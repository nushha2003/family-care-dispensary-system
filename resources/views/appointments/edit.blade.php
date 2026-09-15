@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Edit Appointment</h1>
        <a href="{{ route('appointments.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Back
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('appointments.update', $appointment) }}" method="POST">
                @csrf @method('PUT')
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="patient_id" class="form-label">Patient *</label>
                        <select name="patient_id" id="patient_id" class="form-control @error('patient_id') is-invalid @enderror" required>
                            @foreach($patients as $patient)
                            <option value="{{ $patient->id }}" {{ old('patient_id', $appointment->patient_id)==$patient->id?'selected':'' }}>
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
                            @foreach($doctors as $doctor)
                            <option value="{{ $doctor->id }}" {{ old('doctor_id', $appointment->doctor_id)==$doctor->id?'selected':'' }}>
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
                               value="{{ old('appointment_date', $appointment->appointment_date->format('Y-m-d')) }}" required>
                        @error('appointment_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="time_slot" class="form-label">Time *</label>
                        <input type="time" name="time_slot" id="time_slot"
                               class="form-control @error('time_slot') is-invalid @enderror"
                               value="{{ old('time_slot', $appointment->time_slot->format('H:i')) }}" required>
                        @error('time_slot')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="status" class="form-label">Status</label>
                        <select name="status" id="status" class="form-control @error('status') is-invalid @enderror">
                            <option value="pending" {{ old('status', $appointment->status)=='pending'?'selected':'' }}>Pending</option>
                            <option value="confirmed" {{ old('status', $appointment->status)=='confirmed'?'selected':'' }}>Confirmed</option>
                            <option value="waiting" {{ old('status', $appointment->status)=='waiting'?'selected':'' }}>Waiting</option>
                            <option value="in-consultation" {{ old('status', $appointment->status)=='in-consultation'?'selected':'' }}>In Consultation</option>
                            <option value="completed" {{ old('status', $appointment->status)=='completed'?'selected':'' }}>Completed</option>
                            <option value="cancelled" {{ old('status', $appointment->status)=='cancelled'?'selected':'' }}>Cancelled</option>
                        </select>
                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="notes" class="form-label">Notes</label>
                        <textarea name="notes" id="notes" class="form-control" rows="3">{{ old('notes', $appointment->notes) }}</textarea>
                    </div>
                </div>
                <div class="mt-3">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save"></i> Update Appointment
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection