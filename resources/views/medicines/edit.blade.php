@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Edit Medicine: {{ $medicine->name }}</h1>
        <a href="{{ route('medicines.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Back
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('medicines.update', $medicine) }}" method="POST">
                @csrf @method('PUT')
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="name" class="form-label">Medicine Name *</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror"
                               id="name" name="name" value="{{ old('name', $medicine->name) }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="generic_name" class="form-label">Generic Name</label>
                        <input type="text" class="form-control @error('generic_name') is-invalid @enderror"
                               id="generic_name" name="generic_name" value="{{ old('generic_name', $medicine->generic_name) }}">
                        @error('generic_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="category" class="form-label">Category</label>
                        <input type="text" class="form-control @error('category') is-invalid @enderror"
                               id="category" name="category" value="{{ old('category', $medicine->category) }}">
                        @error('category')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="unit" class="form-label">Unit *</label>
                        <select class="form-control @error('unit') is-invalid @enderror" id="unit" name="unit" required>
                            <option value="">Select Unit</option>
                            <option value="tablet" {{ old('unit', $medicine->unit)=='tablet'?'selected':'' }}>Tablet</option>
                            <option value="capsule" {{ old('unit', $medicine->unit)=='capsule'?'selected':'' }}>Capsule</option>
                            <option value="ml" {{ old('unit', $medicine->unit)=='ml'?'selected':'' }}>ml</option>
                            <option value="mg" {{ old('unit', $medicine->unit)=='mg'?'selected':'' }}>mg</option>
                            <option value="vial" {{ old('unit', $medicine->unit)=='vial'?'selected':'' }}>Vial</option>
                            <option value="bottle" {{ old('unit', $medicine->unit)=='bottle'?'selected':'' }}>Bottle</option>
                            <option value="sachet" {{ old('unit', $medicine->unit)=='sachet'?'selected':'' }}>Sachet</option>
                        </select>
                        @error('unit')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="batch_no" class="form-label">Batch Number</label>
                        <input type="text" class="form-control @error('batch_no') is-invalid @enderror"
                               id="batch_no" name="batch_no" value="{{ old('batch_no', $medicine->batch_no) }}">
                        @error('batch_no')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="purchase_price" class="form-label">Purchase Price *</label>
                        <input type="number" step="0.01" class="form-control @error('purchase_price') is-invalid @enderror"
                               id="purchase_price" name="purchase_price" value="{{ old('purchase_price', $medicine->purchase_price) }}" required>
                        @error('purchase_price')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="selling_price" class="form-label">Selling Price *</label>
                        <input type="number" step="0.01" class="form-control @error('selling_price') is-invalid @enderror"
                               id="selling_price" name="selling_price" value="{{ old('selling_price', $medicine->selling_price) }}" required>
                        @error('selling_price')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="current_stock" class="form-label">Current Stock (read-only)</label>
                        <input type="number" class="form-control" id="current_stock"
                               value="{{ $medicine->current_stock }}" disabled>
                        <small class="text-muted">Use the "Adjust Stock" feature on the detail page to modify.</small>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="reorder_level" class="form-label">Reorder Level *</label>
                        <input type="number" class="form-control @error('reorder_level') is-invalid @enderror"
                               id="reorder_level" name="reorder_level" value="{{ old('reorder_level', $medicine->reorder_level) }}" required>
                        @error('reorder_level')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="expiry_date" class="form-label">Expiry Date</label>
                        <input type="date" class="form-control @error('expiry_date') is-invalid @enderror"
                               id="expiry_date" name="expiry_date" value="{{ old('expiry_date', $medicine->expiry_date ? $medicine->expiry_date->format('Y-m-d') : '') }}">
                        @error('expiry_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="supplier_id" class="form-label">Supplier</label>
                        <select class="form-control @error('supplier_id') is-invalid @enderror"
                                id="supplier_id" name="supplier_id">
                            <option value="">Select Supplier</option>
                            @foreach($suppliers as $supplier)
                            <option value="{{ $supplier->id }}" {{ old('supplier_id', $medicine->supplier_id)==$supplier->id?'selected':'' }}>
                                {{ $supplier->name }}
                            </option>
                            @endforeach
                        </select>
                        @error('supplier_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="mt-3">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save"></i> Update Medicine
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection