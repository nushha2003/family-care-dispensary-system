@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Dispense Medicines</h1>
        <a href="{{ route('prescriptions.show', $prescription) }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Back to Prescription
        </a>
    </div>

    <!-- Prescription Info -->
    <div class="card mb-4">
        <div class="card-body">
            <div class="row">
                <div class="col-md-3">
                    <strong>Prescription #:</strong> {{ $prescription->prescription_number }}
                </div>
                <div class="col-md-3">
                    <strong>Patient:</strong> {{ $prescription->patient->full_name }}
                </div>
                <div class="col-md-3">
                    <strong>Doctor:</strong> {{ $prescription->doctor->name }}
                </div>
                <div class="col-md-3">
                    <strong>Date:</strong> {{ $prescription->issued_date->format('d M Y') }}
                </div>
            </div>
            @if($prescription->notes)
            <div class="row mt-2">
                <div class="col-12">
                    <strong>Notes:</strong> {{ $prescription->notes }}
                </div>
            </div>
            @endif
        </div>
    </div>

    <!-- Dispense Form -->
    <div class="card">
        <div class="card-body">
            <form action="{{ route('dispense.store', $prescription) }}" method="POST">
                @csrf

                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Medicine</th>
                                <th>Stock Available</th>
                                <th>Prescribed Qty</th>
                                <th>Dispense Qty</th>
                                <th>Dosage</th>
                                <th>Frequency</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($prescription->items as $index => $item)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>
                                    {{ $item->medicine->name }}
                                    <input type="hidden" name="dispense_quantities[{{ $item->id }}]" value="{{ $item->quantity }}">
                                </td>
                                <td>
                                    <span class="badge bg-{{ $item->medicine->current_stock <= 0 ? 'danger' : ($item->medicine->current_stock <= $item->medicine->reorder_level ? 'warning' : 'success') }}">
                                        {{ $item->medicine->current_stock }}
                                    </span>
                                </td>
                                <td>{{ $item->quantity }}</td>
                                <td>
                                    <input type="number" name="dispense_quantities[{{ $item->id }}]"
                                           class="form-control @error('dispense_quantities.'.$item->id) is-invalid @enderror"
                                           value="{{ old('dispense_quantities.'.$item->id, $item->quantity) }}"
                                           min="1" max="{{ $item->medicine->current_stock }}" required>
                                    @error('dispense_quantities.'.$item->id)
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </td>
                                <td>{{ $item->dosage }}</td>
                                <td>{{ $item->frequency }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Stock Warning -->
                @php
                    $hasLowStock = $prescription->items->contains(function($item) {
                        return $item->medicine->current_stock < $item->quantity;
                    });
                @endphp

                @if($hasLowStock)
                <div class="alert alert-warning">
                    <i class="bi bi-exclamation-triangle-fill"></i>
                    <strong>Warning:</strong> Some medicines have insufficient stock. Please adjust quantities or inform the doctor.
                </div>
                @endif

                <div class="mt-3">
                    <button type="submit" class="btn btn-success">
                        <i class="bi bi-check-circle"></i> Confirm Dispense
                    </button>
                    <a href="{{ route('prescriptions.show', $prescription) }}" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection