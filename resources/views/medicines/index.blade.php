@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Medicine Stock</h1>
        <a href="{{ route('medicines.create') }}" class="btn btn-success">
            <i class="bi bi-plus-circle"></i> Add New Medicine
        </a>
    </div>

    <!-- Filters -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('medicines.index') }}" class="row g-3">
                <div class="col-md-4">
                    <input type="text" name="search" class="form-control"
                           placeholder="Search by name or generic name"
                           value="{{ request('search') }}">
                </div>
                <div class="col-md-3">
                    <select name="status" class="form-control">
                        <option value="">All Status</option>
                        <option value="low-stock" {{ request('status')=='low-stock'?'selected':'' }}>Low Stock</option>
                        <option value="out-of-stock" {{ request('status')=='out-of-stock'?'selected':'' }}>Out of Stock</option>
                        <option value="expired" {{ request('status')=='expired'?'selected':'' }}>Expired</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">Filter</button>
                </div>
                <div class="col-md-2">
                    <a href="{{ route('medicines.index') }}" class="btn btn-secondary w-100">Reset</a>
                </div>
            </form>
        </div>
    </div>

    <!-- Medicines Table -->
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Generic Name</th>
                            <th>Category</th>
                            <th>Stock</th>
                            <th>Price</th>
                            <th>Expiry</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($medicines as $medicine)
                        <tr>
                            <td>{{ $loop->iteration + ($medicines->currentPage() - 1) * $medicines->perPage() }}</td>
                            <td>{{ $medicine->name }}</td>
                            <td>{{ $medicine->generic_name ?? '-' }}</td>
                            <td>{{ $medicine->category ?? '-' }}</td>
                            <td>{{ $medicine->current_stock }}</td>
                            <td>${{ number_format($medicine->selling_price, 2) }}</td>
                            <td>{{ $medicine->expiry_date ? $medicine->expiry_date->format('d M Y') : '-' }}</td>
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
                            <td>
                                <a href="{{ route('medicines.show', $medicine) }}" class="btn btn-sm btn-info">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('medicines.edit', $medicine) }}" class="btn btn-sm btn-primary">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ route('medicines.destroy', $medicine) }}" method="POST"
                                      style="display:inline-block;"
                                      onsubmit="return confirm('Delete this medicine? This will also delete all related stock movements.')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="text-center">No medicines found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            {{ $medicines->links() }}
        </div>
    </div>
</div>
@endsection