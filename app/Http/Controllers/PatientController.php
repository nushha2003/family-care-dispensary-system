<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PatientController extends Controller
{
    /**
     * Display a listing of patients with search.
     */
    public function index(Request $request)
    {
        $query = Patient::query();

        // Search by name or phone
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $patients = $query->latest()->paginate(10);

        return view('patients.index', compact('patients'));
    }

    /**
     * Show the form for creating a new patient.
     */
    public function create()
    {
        return view('patients.create');
    }

    /**
     * Store a newly created patient in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'dob' => 'required|date|before:today',
            'gender' => ['required', Rule::in(['male', 'female', 'other'])],
            'phone' => 'required|string|max:20|unique:patients,phone',
            'email' => 'nullable|email|max:255|unique:patients,email',
            'address' => 'nullable|string',
            'allergies' => 'nullable|string',
            'chronic_conditions' => 'nullable|string',
            'medical_history' => 'nullable|string',
        ]);

        Patient::create($validated);

        return redirect()->route('patients.index')
            ->with('success', 'Patient registered successfully!');
    }

    /**
     * Display the specified patient.
     */
    public function show(Patient $patient)
    {
        return view('patients.show', compact('patient'));
    }

    /**
     * Show the form for editing the specified patient.
     */
    public function edit(Patient $patient)
    {
        return view('patients.edit', compact('patient'));
    }

    /**
     * Update the specified patient in storage.
     */
    public function update(Request $request, Patient $patient)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'dob' => 'required|date|before:today',
            'gender' => ['required', Rule::in(['male', 'female', 'other'])],
            'phone' => 'required|string|max:20|unique:patients,phone,' . $patient->id,
            'email' => 'nullable|email|max:255|unique:patients,email,' . $patient->id,
            'address' => 'nullable|string',
            'allergies' => 'nullable|string',
            'chronic_conditions' => 'nullable|string',
            'medical_history' => 'nullable|string',
        ]);

        $patient->update($validated);

        return redirect()->route('patients.index')
            ->with('success', 'Patient updated successfully!');
    }

    /**
     * Remove the specified patient from storage.
     */
    public function destroy(Patient $patient)
    {
        $patient->delete();

        return redirect()->route('patients.index')
            ->with('success', 'Patient deleted successfully!');
    }
}