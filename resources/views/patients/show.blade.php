@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Patient Details: {{ $patient->full_name }}</h1>
        <div>
            <a href="{{ route('patients.edit', $patient) }}" class="btn btn-primary">
                <i class="bi bi-pencil"></i> Edit
            </a>
            <a href="{{ route('patients.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Back
            </a>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <h5>Personal Information</h5>
                    <table class="table table-borderless">
                        <tr>
                            <th width="30%">Full Name</th>
                            <td>{{ $patient->full_name }}</td>
                        </tr>
                        <tr>
                            <th>Date of Birth</th>
                            <td>{{ $patient->dob->format('d M, Y') }}</td>
                        </tr>
                        <tr>
                            <th>Gender</th>
                            <td>{{ ucfirst($patient->gender) }}</td>
                        </tr>
                        <tr>
                            <th>Phone</th>
                            <td>{{ $patient->phone }}</td>
                        </tr>
                        <tr>
                            <th>Email</th>
                            <td>{{ $patient->email ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>Address</th>
                            <td>{{ $patient->address ?? 'N/A' }}</td>
                        </tr>
                    </table>
                </div>
                <div class="col-md-6">
                    <h5>Medical Information</h5>
                    <table class="table table-borderless">
                        <tr>
                            <th width="30%">Allergies</th>
                            <td>{{ $patient->allergies ?? 'None' }}</td>
                        </tr>
                        <tr>
                            <th>Chronic Conditions</th>
                            <td>{{ $patient->chronic_conditions ?? 'None' }}</td>
                        </tr>
                        <tr>
                            <th>Medical History</th>
                            <td>{{ $patient->medical_history ?? 'None' }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection