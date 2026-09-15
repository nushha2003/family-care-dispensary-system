@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Daily Revenue Report</h1>
        <div>
            <a href="{{ route('reports.daily') }}" class="btn btn-secondary">Today</a>
            <button onclick="window.print()" class="btn btn-primary">
                <i class="bi bi-printer"></i> Print
            </button>
        </div>
    </div>

    <!-- Date Selector -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('reports.daily') }}" class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Select Date</label>
                    <input type="date" name="date" class="form-control" value="{{ $date->format('Y-m-d') }}">
                </div>
                <div class="col-md-6 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100">Generate Report</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="row">
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card p-3 text-center bg-primary bg-opacity-10 border-primary">
                <h5>Total Revenue</h5>
                <p class="display-4">${{ number_format($stats['total_revenue'], 2) }}</p>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card p-3 text-center bg-success bg-opacity-10 border-success">
                <h5>Medicine Sales</h5>
                <p class="display-4">${{ number_format($stats['total_medicine_sales'], 2) }}</p>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card p-3 text-center bg-info bg-opacity-10 border-info">
                <h5>Prescriptions Dispensed</h5>
                <p class="display-4">{{ $stats['total_prescriptions'] }}</p>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card p-3 text-center bg-warning bg-opacity-10 border-warning">
                <h5>Average per Prescription</h5>
                <p class="display-4">${{ number_format($stats['average_per_prescription'], 2) }}</p>
            </div>
        </div>
    </div>

    <!-- Top Selling Medicines -->
    <div class="card mb-4">
        <div class="card-body">
            <h5>Top Selling Medicines</h5>
            @if(count($medicineSales) > 0)
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Medicine</th>
                            <th>Quantity Sold</th>
                            <th>Revenue</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($medicineSales as $index => $item)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $item['name'] }}</td>
                            <td>{{ $item['quantity'] }}</td>
                            <td>${{ number_format($item['revenue'], 2) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <p class="text-muted">No medicines sold on this date.</p>
            @endif
        </div>
    </div>

    <!-- Detailed Prescriptions List -->
    <div class="card">
        <div class="card-body">
            <h5>Prescriptions Dispensed on {{ $date->format('d M Y') }}</h5>
            @if($prescriptions->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Prescription #</th>
                            <th>Patient</th>
                            <th>Doctor</th>
                            <th>Items</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $grandTotal = 0; @endphp
                        @foreach($prescriptions as $prescription)
                        @php
                            $prescriptionTotal = 0;
                            foreach($prescription->items as $item) {
                                $prescriptionTotal += $item->quantity * $item->medicine->selling_price;
                            }
                            $grandTotal += $prescriptionTotal;
                        @endphp
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $prescription->prescription_number }}</td>
                            <td>{{ $prescription->patient->full_name }}</td>
                            <td>{{ $prescription->doctor->name }}</td>
                            <td>{{ $prescription->items->count() }}</td>
                            <td>${{ number_format($prescriptionTotal, 2) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr class="fw-bold">
                            <td colspan="5" class="text-end">Grand Total</td>
                            <td>${{ number_format($grandTotal, 2) }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
            @else
            <p class="text-muted">No prescriptions dispensed on this date.</p>
            @endif
        </div>
    </div>
</div>
@endsection