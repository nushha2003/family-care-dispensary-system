@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Appointment Details</h1>
        <div>
            <a href="{{ route('appointments.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Back
            </a>
            @if(auth()->user()->role !== 'doctor')
            <a href="{{ route('appointments.edit', $appointment) }}" class="btn btn-primary">
                <i class="bi bi-pencil"></i> Edit
            </a>
            @endif
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <h5>Patient Information</h5>
                    <table class="table table-borderless">
                        <tr>
                            <th width="30%">Name</th>
                            <td>{{ $appointment->patient->full_name }}</td>
                        </tr>
                        <tr>
                            <th>Phone</th>
                            <td>{{ $appointment->patient->phone }}</td>
                        </tr>
                        <tr>
                            <th>Email</th>
                            <td>{{ $appointment->patient->email ?? 'N/A' }}</td>
                        </tr>
                    </table>
                </div>
                <div class="col-md-6">
                    <h5>Appointment Details</h5>
                    <table class="table table-borderless">
                        <tr>
                            <th width="30%">Doctor</th>
                            <td>{{ $appointment->doctor->name }}</td>
                        </tr>
                        <tr>
                            <th>Date</th>
                            <td>{{ $appointment->appointment_date->format('d M Y') }}</td>
                        </tr>
                        <tr>
                            <th>Time</th>
                            <td>{{ $appointment->time_slot->format('h:i A') }}</td>
                        </tr>
                        <tr>
                            <th>Status</th>
                            <td>
                                <span class="badge bg-{{ $appointment->status == 'completed' ? 'success' : ($appointment->status == 'cancelled' ? 'danger' : ($appointment->status == 'in-consultation' ? 'warning' : 'info')) }}">
                                    {{ ucfirst($appointment->status) }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <th>Notes</th>
                            <td>{{ $appointment->notes ?? 'No notes' }}</td>
                        </tr>
                    </table>
                </div>
            </div>

            <!-- Doctor Quick Actions -->
            @if(auth()->user()->role === 'doctor' && in_array($appointment->status, ['pending','confirmed','waiting']))
            <div class="mt-4 p-3 bg-light rounded">
                <h6>Quick Actions</h6>
                <div class="d-flex gap-2">
                    <form action="{{ route('appointments.updateStatus', $appointment) }}" method="POST">
                        @csrf @method('PATCH')
                        <input type="hidden" name="status" value="in-consultation">
                        <button type="submit" class="btn btn-warning">Start Consultation</button>
                    </form>
                    <form action="{{ route('appointments.updateStatus', $appointment) }}" method="POST">
                        @csrf @method('PATCH')
                        <input type="hidden" name="status" value="completed">
                        <button type="submit" class="btn btn-success">Mark Completed</button>
                    </form>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection