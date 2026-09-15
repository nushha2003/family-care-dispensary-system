@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Appointments</h1>
        @if(auth()->user()->role !== 'doctor')
        <a href="{{ route('appointments.create') }}" class="btn btn-success">
            <i class="bi bi-plus-circle"></i> Book Appointment
        </a>
        @endif
    </div>

    <!-- Filters -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('appointments.index') }}" class="row g-3">
                <div class="col-md-3">
                    <label class="form-label">Date</label>
                    <input type="date" name="date" class="form-control" value="{{ request('date', date('Y-m-d')) }}">
                </div>
                <div class="col-md-2">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-control">
                        <option value="">All</option>
                        <option value="pending" {{ request('status')=='pending'?'selected':'' }}>Pending</option>
                        <option value="confirmed" {{ request('status')=='confirmed'?'selected':'' }}>Confirmed</option>
                        <option value="waiting" {{ request('status')=='waiting'?'selected':'' }}>Waiting</option>
                        <option value="in-consultation" {{ request('status')=='in-consultation'?'selected':'' }}>In Consultation</option>
                        <option value="completed" {{ request('status')=='completed'?'selected':'' }}>Completed</option>
                        <option value="cancelled" {{ request('status')=='cancelled'?'selected':'' }}>Cancelled</option>
                    </select>
                </div>
                @if(auth()->user()->role !== 'doctor')
                <div class="col-md-2">
                    <label class="form-label">Doctor</label>
                    <select name="doctor_id" class="form-control">
                        <option value="">All</option>
                        @foreach($doctors as $doc)
                        <option value="{{ $doc->id }}" {{ request('doctor_id')==$doc->id?'selected':'' }}>
                            {{ $doc->name }}
                        </option>
                        @endforeach
                    </select>
                </div>
                @endif
                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100">Filter</button>
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <a href="{{ route('appointments.index') }}" class="btn btn-secondary w-100">Reset</a>
                </div>
            </form>
        </div>
    </div>

    <!-- Appointments Table -->
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Patient</th>
                            <th>Doctor</th>
                            <th>Date</th>
                            <th>Time</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($appointments as $appt)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $appt->patient->full_name }}</td>
                            <td>{{ $appt->doctor->name }}</td>
                            <td>{{ $appt->appointment_date->format('d M Y') }}</td>
                            <td>{{ $appt->time_slot->format('h:i A') }}</td>
                            <td>
                                <span class="badge bg-{{ $appt->status == 'completed' ? 'success' : ($appt->status == 'cancelled' ? 'danger' : ($appt->status == 'in-consultation' ? 'warning' : 'info')) }}">
                                    {{ ucfirst($appt->status) }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('appointments.show', $appt) }}" class="btn btn-sm btn-info">
                                    <i class="bi bi-eye"></i>
                                </a>
                                @if(auth()->user()->role !== 'doctor')
                                <a href="{{ route('appointments.edit', $appt) }}" class="btn btn-sm btn-primary">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                @endif
                                @if(auth()->user()->role === 'doctor' && in_array($appt->status, ['pending','confirmed','waiting']))
                                <form action="{{ route('appointments.updateStatus', $appt) }}" method="POST" style="display:inline-block;">
                                    @csrf @method('PATCH')
                                    <input type="hidden" name="status" value="in-consultation">
                                    <button type="submit" class="btn btn-sm btn-warning">Start</button>
                                </form>
                                <form action="{{ route('appointments.updateStatus', $appt) }}" method="POST" style="display:inline-block;">
                                    @csrf @method('PATCH')
                                    <input type="hidden" name="status" value="completed">
                                    <button type="submit" class="btn btn-sm btn-success">Complete</button>
                                </form>
                                @endif
                                <form action="{{ route('appointments.destroy', $appt) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Cancel this appointment?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger"><i class="bi bi-x-circle"></i></button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="7" class="text-center">No appointments found for the selected filters.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection