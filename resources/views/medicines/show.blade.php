@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>{{ $medicine->name }}</h1>
        <div>
            <a href="{{ route('medicines.edit', $medicine) }}" class="btn btn-primary">
                <i class="bi bi-pencil"></i> Edit
            </a>
            <a href="{{ route('medicines.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Back
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h5>Details</h5>
                    <table class="table table-borderless">
                        <tr>
                            <th width="30%">Name</th>
                            <td>{{ $medicine->name }}</td>
                        </tr>
                        <tr>
                            <th>Generic Name</th>
                            <td>{{ $medicine->generic_name ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Category</th>
                            <td>{{ $medicine->category ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Unit</th>
                            <td>{{ ucfirst($medicine->unit) }}</td>
                        </tr>
                        <tr>
                            <th>Batch No.</th>
                            <td>{{ $medicine->batch_no ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Supplier</th>
                            <td>{{ $medicine->supplier->name ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>Expiry Date</th>
                            <td>{{ $medicine->expiry_date ? $medicine->expiry_date->format('d M Y') : '-' }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h5>Stock Information</h5>
                    <table class="table table-borderless">
                        <tr>
                            <th width="30%">Current Stock</th>
                            <td>
                                <span class="badge bg-{{ $medicine->current_stock <= 0 ? 'danger' : ($medicine->current_stock <= $medicine->reorder_level ? 'warning' : 'success') }}
                                    p-2 fs-6">
                                    {{ $medicine->current_stock }} {{ ucfirst($medicine->unit) }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <th>Reorder Level</th>
                            <td>{{ $medicine->reorder_level }}</td>
                        </tr>
                        <tr>
                            <th>Purchase Price</th>
                            <td>${{ number_format($medicine->purchase_price, 2) }}</td>
                        </tr>
                        <tr>
                            <th>Selling Price</th>
                            <td>${{ number_format($medicine->selling_price, 2) }}</td>
                        </tr>
                        <tr>
                            <th>Status</th>
                            <td>
                                @if($medicine->current_stock <= 0)
                                    <span class="badge bg-danger">Out of Stock</span>
                                @elseif($medicine->current_stock <= $medicine->reorder_level)
                                    <span class="badge bg-warning">Low Stock</span>
                                @elseif($medicine->expiry_date && $medicine->expiry_date->isPast())
                                    <span class="badge bg-secondary">Expired</span>
                                @else
                                    <span class="badge bg-success">In Stock</span>
                                @endif
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Adjust Stock Section -->
    <div class="card mt-4">
        <div class="card-body">
            <h5>Adjust Stock</h5>
            <form action="{{ route('medicines.adjustStock', $medicine) }}" method="POST" class="row g-3">
                @csrf
                <div class="col-md-3">
                    <input type="number" name="quantity" class="form-control"
                           placeholder="Quantity (positive add, negative remove)" required>
                </div>
                <div class="col-md-5">
                    <input type="text" name="note" class="form-control" placeholder="Reason for adjustment (optional)">
                </div>
                <div class="col-md-4">
                    <button type="submit" class="btn btn-warning w-100">
                        <i class="bi bi-arrow-repeat"></i> Adjust Stock
                    </button>
                </div>
            </form>
            <small class="text-muted">Enter a positive number to add stock, negative to remove (e.g., -5).</small>
        </div>
    </div>

    <!-- Stock Movements History -->
    <div class="card mt-4">
        <div class="card-body">
            <h5>Stock Movement History</h5>
            <div class="table-responsive">
                <table class="table table-sm">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Change</th>
                            <th>Type</th>
                            <th>User</th>
                            <th>Note</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($medicine->stockMovements as $movement)
                        <tr>
                            <td>{{ $movement->created_at->format('d M Y h:i A') }}</td>
                            <td>
                                <span class="badge bg-{{ $movement->quantity_change > 0 ? 'success' : 'danger' }}">
                                    {{ $movement->quantity_change > 0 ? '+' : '' }}{{ $movement->quantity_change }}
                                </span>
                            </td>
                            <td>{{ ucfirst($movement->movement_type) }}</td>
                            <td>{{ $movement->user->name }}</td>
                            <td>{{ $movement->note ?? '-' }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="5" class="text-center">No movements recorded yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection