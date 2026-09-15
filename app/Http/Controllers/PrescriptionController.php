<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Models\Prescription;
use App\Models\PrescriptionItem;
use App\Models\Medicine;
use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Carbon\Carbon;

class PrescriptionController extends Controller
{
    /**
     * Display a list of prescriptions.
     */
    public function index(Request $request)
    {
        $query = Prescription::with(['patient', 'doctor']);

        if ($request->filled('patient_id')) {
            $query->where('patient_id', $request->patient_id);
        }

        if (auth()->user()->role === 'doctor') {
            $query->where('doctor_id', auth()->id());
        }

        $prescriptions = $query->latest()->paginate(15);
        $patients = Patient::orderBy('first_name')->get();

        return view('prescriptions.index', compact('prescriptions', 'patients'));
    }

    /**
     * Show the prescription creation form.
     */
    public function create(Request $request)
    {
        $patient = null;
        if ($request->filled('patient_id')) {
            $patient = Patient::findOrFail($request->patient_id);
        }

        $patients = Patient::orderBy('first_name')->get();
        $medicines = Medicine::where('current_stock', '>', 0)
            ->orderBy('name')
            ->get();

        // Fetch patient's allergy warnings if patient is selected
        $allergyWarnings = [];
        if ($patient) {
            $allergies = explode(',', $patient->allergies ?? '');
            $allergyWarnings = Medicine::whereIn('category', $allergies)
                ->orWhere('generic_name', 'LIKE', '%' . $patient->allergies . '%')
                ->pluck('name')
                ->toArray();
        }

        return view('prescriptions.create', compact('patient', 'patients', 'medicines', 'allergyWarnings'));
    }

    /**
     * Store a new prescription.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'appointment_id' => 'nullable|exists:appointments,id',
            'notes' => 'nullable|string',
            'medicines' => 'required|array|min:1',
            'medicines.*.medicine_id' => 'required|exists:medicines,id',
            'medicines.*.dosage' => 'required|string|max:255',
            'medicines.*.frequency' => 'required|string|max:255',
            'medicines.*.duration' => 'required|integer|min:1',
            'medicines.*.quantity' => 'required|integer|min:1',
            'medicines.*.instructions' => 'nullable|string',
        ]);

        // Check stock availability before creating
        foreach ($validated['medicines'] as $item) {
            $medicine = Medicine::find($item['medicine_id']);
            if ($medicine->current_stock < $item['quantity']) {
                return back()->withErrors([
                    'medicines' => "Insufficient stock for {$medicine->name}. Available: {$medicine->current_stock}"
                ]);
            }
        }

        // Create the prescription
        $prescription = Prescription::create([
            'prescription_number' => 'RX-' . date('Ymd') . '-' . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT),
            'patient_id' => $validated['patient_id'],
            'doctor_id' => auth()->id(),
            'appointment_id' => $validated['appointment_id'] ?? null,
            'issued_date' => Carbon::today(),
            'valid_until' => Carbon::today()->addDays(30),
            'notes' => $validated['notes'] ?? null,
            'is_dispensed' => false,
        ]);

        // Create prescription items
        foreach ($validated['medicines'] as $item) {
            PrescriptionItem::create([
                'prescription_id' => $prescription->id,
                'medicine_id' => $item['medicine_id'],
                'dosage' => $item['dosage'],
                'frequency' => $item['frequency'],
                'duration' => $item['duration'],
                'quantity' => $item['quantity'],
                'instructions' => $item['instructions'] ?? null,
            ]);
        }

        return redirect()->route('prescriptions.show', $prescription)
            ->with('success', 'Prescription created successfully!');
    }

    /**
     * Show a specific prescription.
     */
    public function show(Prescription $prescription)
    {
        $prescription->load(['patient', 'doctor', 'items.medicine']);
        $canDispense = !$prescription->is_dispensed && auth()->user()->role !== 'doctor';

        return view('prescriptions.show', compact('prescription', 'canDispense'));
    }

    /**
     * Edit prescription.
     */
    public function edit(Prescription $prescription)
    {
        if (auth()->user()->role === 'doctor' && $prescription->is_dispensed) {
            abort(403, 'Cannot edit a dispensed prescription.');
        }

        $patients = Patient::orderBy('first_name')->get();
        $medicines = Medicine::where('current_stock', '>', 0)->orderBy('name')->get();
        return view('prescriptions.edit', compact('prescription', 'patients', 'medicines'));
    }

    /**
     * Update prescription.
     */
    public function update(Request $request, Prescription $prescription)
    {
        if ($prescription->is_dispensed) {
            return back()->with('error', 'Cannot update a dispensed prescription.');
        }

        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'notes' => 'nullable|string',
            'medicines' => 'required|array|min:1',
            'medicines.*.medicine_id' => 'required|exists:medicines,id',
            'medicines.*.dosage' => 'required|string|max:255',
            'medicines.*.frequency' => 'required|string|max:255',
            'medicines.*.duration' => 'required|integer|min:1',
            'medicines.*.quantity' => 'required|integer|min:1',
            'medicines.*.instructions' => 'nullable|string',
        ]);

        // Update basic info
        $prescription->update([
            'patient_id' => $validated['patient_id'],
            'notes' => $validated['notes'] ?? null,
        ]);

        // Delete old items and create new ones
        $prescription->items()->delete();
        foreach ($validated['medicines'] as $item) {
            PrescriptionItem::create([
                'prescription_id' => $prescription->id,
                'medicine_id' => $item['medicine_id'],
                'dosage' => $item['dosage'],
                'frequency' => $item['frequency'],
                'duration' => $item['duration'],
                'quantity' => $item['quantity'],
                'instructions' => $item['instructions'] ?? null,
            ]);
        }

        return redirect()->route('prescriptions.show', $prescription)
            ->with('success', 'Prescription updated!');
    }

    /**
     * Delete prescription.
     */
    public function destroy(Prescription $prescription)
    {
        if ($prescription->is_dispensed) {
            return back()->with('error', 'Cannot delete a dispensed prescription.');
        }
        $prescription->items()->delete();
        $prescription->delete();
        return redirect()->route('prescriptions.index')
            ->with('success', 'Prescription deleted.');
    }
}