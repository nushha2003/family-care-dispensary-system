@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Prescription Details</h1>
        <div>
            <a href="{{ route('prescriptions.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Back
            </a>
            @if(!$prescription->is_dispensed && auth()->user()->role !== 'receptionist')
                <a href="{{ route('prescriptions.edit', $prescription) }}" class="btn btn-primary">
                    <i class="bi bi-pencil"></i> Edit
                </a>
            @endif
        </div>
    </div>

    <!-- Prescription Header Info -->
    <div class="card mb-4">
        <div class="card-body">
            <div class="row">
                <div class="col-md-4">
                    <strong>Prescription #:</strong> {{ $prescription->prescription_number }}
                </div>
                <div class="col-md-4">
                    <strong>Date:</strong> {{ $prescription->issued_date->format('d M Y') }}
                </div>
                <div class="col-md-4">
                    <strong>Status:</strong>
                    <span class="badge bg-{{ $prescription->is_dispensed ? 'success' : 'warning' }}">
                        {{ $prescription->is_dispensed ? 'Dispensed' : 'Pending' }}
                    </span>
                </div>
                <div class="col-md-4">
                    <strong>Patient:</strong> {{ $prescription->patient->full_name }}
                </div>
                <div class="col-md-4">
                    <strong>Doctor:</strong> {{ $prescription->doctor->name }}
                </div>
                <div class="col-md-4">
                    <strong>Valid Until:</strong> {{ $prescription->valid_until ? $prescription->valid_until->format('d M Y') : 'N/A' }}
                </div>
                @if($prescription->notes)
                <div class="col-md-12 mt-2">
                    <strong>Notes:</strong> {{ $prescription->notes }}
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Prescription Items -->
    <div class="card">
        <div class="card-body">
            <h5>Prescribed Medicines</h5>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Medicine</th>
                            <th>Dosage</th>
                            <th>Frequency</th>
                            <th>Duration (days)</th>
                            <th>Quantity</th>
                            <th>Instructions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($prescription->items as $item)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $item->medicine->name }}</td>
                            <td>{{ $item->dosage }}</td>
                            <td>{{ $item->frequency }}</td>
                            <td>{{ $item->duration }}</td>
                            <td>{{ $item->quantity }}</td>
                            <td>{{ $item->instructions ?? '-' }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="7" class="text-center">No medicines in this prescription.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Action Buttons (Dispense / Receipt) -->
    <div class="mt-4">
        @if(!$prescription->is_dispensed && auth()->user()->role !== 'doctor')
            <a href="{{ route('dispense.create', $prescription) }}" class="btn btn-success">
                <i class="bi bi-capsule"></i> Dispense Medicines
            </a>
        @endif

        @if($prescription->is_dispensed)
            <a href="{{ route('receipt.generate', $prescription) }}" class="btn btn-info" target="_blank">
                <i class="bi bi-download"></i> Download Receipt (PDF)
            </a>
        @endif

        @if(!$prescription->is_dispensed && auth()->user()->role !== 'receptionist')
            <form action="{{ route('prescriptions.destroy', $prescription) }}" method="POST" style="display:inline-block;"
                  onsubmit="return confirm('Delete this prescription? This action cannot be undone.')">
                @csrf @method('DELETE')
                <button type="submit" class="btn btn-danger">
                    <i class="bi bi-trash"></i> Delete
                </button>
            </form>
        @endif
    </div>
</div>
@endsection