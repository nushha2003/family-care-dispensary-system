@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Register New Patient</h1>
        <a href="{{ route('patients.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Back
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('patients.store') }}" method="POST">
                @csrf
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="first_name" class="form-label">First Name *</label>
                        <input type="text" class="form-control @error('first_name') is-invalid @enderror"
                               id="first_name" name="first_name" value="{{ old('first_name') }}" required>
                        @error('first_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="last_name" class="form-label">Last Name *</label>
                        <input type="text" class="form-control @error('last_name') is-invalid @enderror"
                               id="last_name" name="last_name" value="{{ old('last_name') }}" required>
                        @error('last_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="dob" class="form-label">Date of Birth *</label>
                        <input type="date" class="form-control @error('dob') is-invalid @enderror"
                               id="dob" name="dob" value="{{ old('dob') }}" required>
                        @error('dob')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="gender" class="form-label">Gender *</label>
                        <select class="form-control @error('gender') is-invalid @enderror"
                                id="gender" name="gender" required>
                            <option value="">Select Gender</option>
                            <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Male</option>
                            <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Female</option>
                            <option value="other" {{ old('gender') == 'other' ? 'selected' : '' }}>Other</option>
                        </select>
                        @error('gender')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="phone" class="form-label">Phone *</label>
                        <input type="text" class="form-control @error('phone') is-invalid @enderror"
                               id="phone" name="phone" value="{{ old('phone') }}" required>
                        @error('phone')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror"
                               id="email" name="email" value="{{ old('email') }}">
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="address" class="form-label">Address</label>
                        <input type="text" class="form-control @error('address') is-invalid @enderror"
                               id="address" name="address" value="{{ old('address') }}">
                        @error('address')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="allergies" class="form-label">Allergies</label>
                        <input type="text" class="form-control @error('allergies') is-invalid @enderror"
                               id="allergies" name="allergies" value="{{ old('allergies') }}"
                               placeholder="e.g., Penicillin, Aspirin">
                        @error('allergies')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="chronic_conditions" class="form-label">Chronic Conditions</label>
                        <input type="text" class="form-control @error('chronic_conditions') is-invalid @enderror"
                               id="chronic_conditions" name="chronic_conditions" value="{{ old('chronic_conditions') }}"
                               placeholder="e.g., Diabetes, Hypertension">
                        @error('chronic_conditions')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="medical_history" class="form-label">Medical History</label>
                        <input type="text" class="form-control @error('medical_history') is-invalid @enderror"
                               id="medical_history" name="medical_history" value="{{ old('medical_history') }}"
                               placeholder="Past surgeries, etc.">
                        @error('medical_history')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="mt-3">
                    <button type="submit" class="btn btn-success">
                        <i class="bi bi-check-circle"></i> Register Patient
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection