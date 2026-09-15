@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>New Prescription</h1>
        <a href="{{ route('prescriptions.index') }}" class="btn btn-secondary">Back</a>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('prescriptions.store') }}" method="POST" id="prescriptionForm">
                @csrf
                <div class="row">
                    <!-- Patient Select -->
                    <div class="col-md-6 mb-3">
                        <label for="patient_id" class="form-label">Patient *</label>
                        <select name="patient_id" id="patient_id" class="form-control" required>
                            <option value="">Select Patient</option>
                            @foreach($patients as $p)
                            <option value="{{ $p->id }}" {{ ($patient && $patient->id == $p->id) ? 'selected' : '' }}>
                                {{ $p->full_name }} ({{ $p->phone }})
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="notes" class="form-label">Notes</label>
                        <textarea name="notes" class="form-control" rows="2"></textarea>
                    </div>
                </div>

                <!-- Allergy Warning -->
                @if(!empty($allergyWarnings))
                <div class="alert alert-danger">
                    <i class="bi bi-exclamation-triangle-fill"></i>
                    <strong>Allergy Alert!</strong> This patient is allergic to:
                    <ul>
                        @foreach($allergyWarnings as $warning)
                        <li>{{ $warning }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                <hr>
                <h5>Prescription Items</h5>
                <div id="itemsContainer">
                    <div class="row item-row mb-2">
                        <div class="col-md-3">
                            <select name="medicines[0][medicine_id]" class="form-control medicine-select" required>
                                <option value="">Select Medicine</option>
                                @foreach($medicines as $m)
                                <option value="{{ $m->id }}" data-stock="{{ $m->current_stock }}">
                                    {{ $m->name }} (Stock: {{ $m->current_stock }})
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <input type="text" name="medicines[0][dosage]" class="form-control" placeholder="Dosage (e.g., 500mg)" required>
                        </div>
                        <div class="col-md-2">
                            <input type="text" name="medicines[0][frequency]" class="form-control" placeholder="Frequency (e.g., twice daily)" required>
                        </div>
                        <div class="col-md-1">
                            <input type="number" name="medicines[0][duration]" class="form-control" placeholder="Days" required>
                        </div>
                        <div class="col-md-2">
                            <input type="number" name="medicines[0][quantity]" class="form-control" placeholder="Qty" required>
                        </div>
                        <div class="col-md-2">
                            <button type="button" class="btn btn-danger remove-item">Remove</button>
                        </div>
                    </div>
                </div>

                <button type="button" id="addItem" class="btn btn-primary mt-2">
                    <i class="bi bi-plus-circle"></i> Add Medicine
                </button>

                <div class="mt-4">
                    <button type="submit" class="btn btn-success">Save Prescription</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // Add dynamic items
    let itemIndex = 1;
    document.getElementById('addItem').addEventListener('click', function() {
        const container = document.getElementById('itemsContainer');
        const row = container.querySelector('.item-row:last-child').cloneNode(true);
        // Update name attributes
        row.querySelectorAll('[name*="medicines[0]"]').forEach(el => {
            el.name = el.name.replace('[0]', `[${itemIndex}]`);
            if (el.tagName === 'SELECT') el.selectedIndex = 0;
            else el.value = '';
        });
        container.appendChild(row);
        itemIndex++;
    });

    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-item')) {
            const rows = document.querySelectorAll('.item-row');
            if (rows.length > 1) e.target.closest('.item-row').remove();
        }
    });
</script>
@endsection