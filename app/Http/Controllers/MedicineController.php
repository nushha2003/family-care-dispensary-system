<?php

namespace App\Http\Controllers;

use App\Models\Medicine;
use App\Models\Supplier;
use App\Models\StockMovement;
use Illuminate\Http\Request;

class MedicineController extends Controller
{
    public function index(Request $request)
    {
        $query = Medicine::with('supplier');

        if ($request->filled('search')) {
            $query->where('name', 'LIKE', "%{$request->search}%")
                  ->orWhere('generic_name', 'LIKE', "%{$request->search}%");
        }

        if ($request->filled('status')) {
            if ($request->status === 'low-stock') {
                $query->whereRaw('current_stock <= reorder_level');
            } elseif ($request->status === 'out-of-stock') {
                $query->where('current_stock', 0);
            } elseif ($request->status === 'expired') {
                $query->where('expiry_date', '<', now());
            }
        }

        $medicines = $query->orderBy('name')->paginate(15);
        $suppliers = Supplier::all();
        return view('medicines.index', compact('medicines', 'suppliers'));
    }

    public function create()
    {
        $suppliers = Supplier::all();
        return view('medicines.create', compact('suppliers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'generic_name' => 'nullable|string|max:255',
            'category' => 'nullable|string|max:255',
            'unit' => 'required|string|max:50',
            'purchase_price' => 'required|numeric|min:0',
            'selling_price' => 'required|numeric|min:0',
            'current_stock' => 'required|integer|min:0',
            'reorder_level' => 'required|integer|min:0',
            'expiry_date' => 'nullable|date|after:today',
            'batch_no' => 'nullable|string|max:50',
            'supplier_id' => 'nullable|exists:suppliers,id',
        ]);

        $medicine = Medicine::create($validated);

        // Log initial stock movement if stock > 0
        if ($validated['current_stock'] > 0) {
            StockMovement::add(
                $medicine->id,
                $validated['current_stock'],
                auth()->id(),
                'Initial stock entry'
            );
        }

        return redirect()->route('medicines.index')
            ->with('success', 'Medicine added successfully!');
    }

    public function show(Medicine $medicine)
    {
        $medicine->load('stockMovements.user');
        return view('medicines.show', compact('medicine'));
    }

    public function edit(Medicine $medicine)
    {
        $suppliers = Supplier::all();
        return view('medicines.edit', compact('medicine', 'suppliers'));
    }

    public function update(Request $request, Medicine $medicine)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'generic_name' => 'nullable|string|max:255',
            'category' => 'nullable|string|max:255',
            'unit' => 'required|string|max:50',
            'purchase_price' => 'required|numeric|min:0',
            'selling_price' => 'required|numeric|min:0',
            'reorder_level' => 'required|integer|min:0',
            'expiry_date' => 'nullable|date|after:today',
            'batch_no' => 'nullable|string|max:50',
            'supplier_id' => 'nullable|exists:suppliers,id',
        ]);

        $medicine->update($validated);

        return redirect()->route('medicines.index')
            ->with('success', 'Medicine updated!');
    }

    public function destroy(Medicine $medicine)
    {
        if ($medicine->current_stock > 0) {
            return back()->with('error', 'Cannot delete medicine with stock.');
        }
        $medicine->delete();
        return redirect()->route('medicines.index')
            ->with('success', 'Medicine deleted.');
    }

    // Stock adjustment (manual add/remove)
    public function adjustStock(Request $request, Medicine $medicine)
    {
        $request->validate([
            'quantity' => 'required|integer|not_in:0',
            'note' => 'nullable|string',
        ]);

        $quantity = (int) $request->quantity;
        $type = $quantity > 0 ? 'purchase' : 'adjustment';
        $note = $request->note ?? ($quantity > 0 ? 'Stock added' : 'Stock removed');

        if ($quantity < 0 && ($medicine->current_stock + $quantity) < 0) {
            return back()->with('error', 'Insufficient stock to remove.');
        }

        $medicine->increment('current_stock', $quantity);

        StockMovement::create([
            'medicine_id' => $medicine->id,
            'user_id' => auth()->id(),
            'quantity_change' => $quantity,
            'movement_type' => $type,
            'note' => $note,
        ]);

        return redirect()->route('medicines.show', $medicine)
            ->with('success', 'Stock adjusted successfully.');
    }
}