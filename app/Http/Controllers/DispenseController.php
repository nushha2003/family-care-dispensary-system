<?php

namespace App\Http\Controllers;

use App\Models\Prescription;
use App\Models\PrescriptionItem;
use App\Models\StockMovement;
use Illuminate\Http\Request;

class DispenseController extends Controller
{
    /**
     * Show the dispense form.
     */
    public function create(Prescription $prescription)
    {
        if ($prescription->is_dispensed) {
            return redirect()->back()->with('error', 'Already dispensed.');
        }

        $prescription->load(['patient', 'items.medicine']);
        return view('dispense.create', compact('prescription'));
    }

    /**
     * Process dispense and deduct stock.
     */
    public function store(Request $request, Prescription $prescription)
    {
        if ($prescription->is_dispensed) {
            return redirect()->back()->with('error', 'Already dispensed.');
        }

        $request->validate([
            'dispense_quantities' => 'required|array',
            'dispense_quantities.*' => 'required|integer|min:1',
        ]);

        $prescription->load('items.medicine');

        // Check stock and deduct
        foreach ($prescription->items as $item) {
            $dispensedQty = $request->dispense_quantities[$item->id] ?? $item->quantity;

            if ($dispensedQty > $item->medicine->current_stock) {
                return back()->withErrors([
                    "Insufficient stock for {$item->medicine->name}. Available: {$item->medicine->current_stock}"
                ]);
            }
        }

        // Perform stock deduction and create movements
        foreach ($prescription->items as $item) {
            $dispensedQty = $request->dispense_quantities[$item->id] ?? $item->quantity;

            StockMovement::deduct(
                $item->medicine_id,
                $dispensedQty,
                auth()->id(),
                'prescription',
                $prescription->id,
                "Dispensed for Prescription #{$prescription->prescription_number}"
            );

            // Optionally update the item quantity if partially dispensed
            if ($dispensedQty != $item->quantity) {
                $item->update(['quantity' => $dispensedQty]);
            }
        }

        // Mark prescription as dispensed
        $prescription->update(['is_dispensed' => true]);

        return redirect()->route('prescriptions.show', $prescription)
            ->with('success', 'Medicines dispensed successfully! Stock updated.');
    }
}