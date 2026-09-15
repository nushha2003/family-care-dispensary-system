@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Prescriptions</h1>
        <a href="{{ route('prescriptions.create') }}" class="btn btn-success">
            <i class="bi bi-plus-circle"></i> New Prescription
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Prescription #</th>
                            <th>Patient</th>
                            <th>Doctor</th>
                            <th>Date</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($prescriptions as $pres)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $pres->prescription_number }}</td>
                            <td>{{ $pres->patient->full_name }}</td>
                            <td>{{ $pres->doctor->name }}</td>
                            <td>{{ $pres->issued_date->format('d M Y') }}</td>
                            <td>
                                <span class="badge bg-{{ $pres->is_dispensed ? 'success' : 'warning' }}">
                                    {{ $pres->is_dispensed ? 'Dispensed' : 'Pending' }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('prescriptions.show', $pres) }}" class="btn btn-sm btn-info">
                                    <i class="bi bi-eye"></i>
                                </a>
                                @if(!$pres->is_dispensed && auth()->user()->role !== 'receptionist')
                                <a href="{{ route('prescriptions.edit', $pres) }}" class="btn btn-sm btn-primary">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="7" class="text-center">No prescriptions found.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            {{ $prescriptions->links() }}
        </div>
    </div>
</div>
@endsection