<?php

namespace App\Http\Controllers;

use App\Models\Prescription;
use Barryvdh\DomPDF\Facade\Pdf;

class ReceiptController extends Controller
{
    public function generateReceipt(Prescription $prescription)
    {
        $prescription->load(['patient', 'doctor', 'items.medicine']);

        $data = [
            'prescription' => $prescription,
            'date' => now()->format('d M Y, h:i A'),
            'receipt_no' => 'RCP-' . str_pad($prescription->id, 5, '0', STR_PAD_LEFT),
        ];

        $pdf = Pdf::loadView('pdf.receipt', $data);
        return $pdf->download('receipt-' . $prescription->prescription_number . '.pdf');
    }
}