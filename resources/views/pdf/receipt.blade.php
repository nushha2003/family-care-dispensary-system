<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Medical Receipt</title>
    <style>
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            padding: 40px;
            color: #1a1a1a;
        }
        .receipt-container {
            max-width: 700px;
            margin: 0 auto;
            border: 2px solid #2e7d32;
            border-radius: 12px;
            padding: 40px;
            background: white;
        }
        .header {
            text-align: center;
            border-bottom: 3px solid #2e7d32;
            padding-bottom: 20px;
            margin-bottom: 20px;
        }
        .header h1 {
            color: #2e7d32;
            font-size: 28px;
            margin: 0;
            font-weight: 700;
        }
        .header p {
            margin: 5px 0;
            color: #666;
            font-size: 14px;
        }
        .receipt-title {
            background: #2e7d32;
            color: white;
            padding: 10px 20px;
            border-radius: 8px;
            text-align: center;
            margin: 20px 0;
            font-size: 20px;
            font-weight: 600;
        }
        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px 20px;
            margin-bottom: 25px;
            font-size: 14px;
        }
        .info-grid .label {
            font-weight: 700;
            color: #555;
        }
        .info-grid .value {
            color: #1a1a1a;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            font-size: 13px;
        }
        table thead {
            background: #2e7d32;
            color: white;
        }
        table th {
            padding: 12px 10px;
            text-align: left;
            font-weight: 600;
        }
        table td {
            padding: 10px;
            border-bottom: 1px solid #e0e0e0;
        }
        table tbody tr:last-child td {
            border-bottom: none;
        }
        .total-row {
            font-weight: 700;
            font-size: 16px;
            border-top: 2px solid #2e7d32;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 12px;
            color: #888;
            border-top: 1px solid #ddd;
            padding-top: 20px;
        }
        .status-badge {
            display: inline-block;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }
        .status-dispensed {
            background: #4caf50;
            color: white;
        }
        .status-pending {
            background: #ff9800;
            color: white;
        }
        .clinic-name {
            font-size: 12px;
            color: #888;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .mt-20 {
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="receipt-container">
        <!-- Header -->
        <div class="header">
            <h1><i>🏥</i> FamilyCare</h1>
            <p>123 Healthcare Avenue, Medical City</p>
            <p>📞 +1 234 567 890 | 📧 info@Familycare.com</p>
        </div>

        <!-- Receipt Title -->
        <div class="receipt-title">
            <i class="bi bi-receipt"></i> Dispensary Receipt
        </div>

        <!-- Receipt Details -->
        <div class="info-grid">
            <div><span class="label">Receipt #:</span> <span class="value">{{ $receipt_no }}</span></div>
            <div><span class="label">Date:</span> <span class="value">{{ $date }}</span></div>
            <div><span class="label">Patient:</span> <span class="value">{{ $prescription->patient->full_name }}</span></div>
            <div><span class="label">Doctor:</span> <span class="value">{{ $prescription->doctor->name }}</span></div>
            <div><span class="label">Prescription #:</span> <span class="value">{{ $prescription->prescription_number }}</span></div>
            <div>
                <span class="label">Status:</span>
                <span class="status-badge {{ $prescription->is_dispensed ? 'status-dispensed' : 'status-pending' }}">
                    {{ $prescription->is_dispensed ? '✅ Dispensed' : '⏳ Pending' }}
                </span>
            </div>
        </div>

        <!-- Medicines Table -->
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Medicine</th>
                    <th>Dosage</th>
                    <th>Frequency</th>
                    <th>Qty</th>
                    <th class="text-right">Price</th>
                    <th class="text-right">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @php $total = 0; @endphp
                @foreach($prescription->items as $item)
                @php
                    $subtotal = $item->quantity * $item->medicine->selling_price;
                    $total += $subtotal;
                @endphp
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $item->medicine->name }}</td>
                    <td>{{ $item->dosage }}</td>
                    <td>{{ $item->frequency }}</td>
                    <td>{{ $item->quantity }}</td>
                    <td class="text-right">${{ number_format($item->medicine->selling_price, 2) }}</td>
                    <td class="text-right">${{ number_format($subtotal, 2) }}</td>
                </tr>
                @endforeach
                <tr class="total-row">
                    <td colspan="6" class="text-right"><strong>Total</strong></td>
                    <td class="text-right"><strong>${{ number_format($total, 2) }}</strong></td>
                </tr>
            </tbody>
        </table>

        <!-- Notes -->
        @if($prescription->notes)
        <div style="margin-top:15px; padding:10px; background:#f5f5f5; border-radius:8px; font-size:13px;">
            <strong>📝 Notes:</strong> {{ $prescription->notes }}
        </div>
        @endif

        <!-- Footer -->
        <div class="footer">
            <p>Thank you for choosing FamilyCare. Stay healthy! 💚</p>
            <p class="clinic-name">This is a system-generated receipt. For inquiries, please contact us.</p>
        </div>
    </div>
</body>
</html>