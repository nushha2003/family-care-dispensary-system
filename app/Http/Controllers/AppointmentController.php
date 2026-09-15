<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Patient;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Carbon\Carbon;

class AppointmentController extends Controller
{
    /**
     * Display a listing of appointments with filters.
     */
    public function index(Request $request)
    {
        $query = Appointment::with(['patient', 'doctor']);

        // Filter by date
        if ($request->filled('date')) {
            $query->whereDate('appointment_date', $request->date);
        } else {
            $query->whereDate('appointment_date', Carbon::today());
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Doctor filter (for admin/reception)
        if ($request->filled('doctor_id')) {
            $query->where('doctor_id', $request->doctor_id);
        }

        // If logged-in user is a doctor, only show their appointments
        if (auth()->user()->role === 'doctor') {
            $query->where('doctor_id', auth()->id());
        }

        $appointments = $query->latest('appointment_date')->get();

        $patients = Patient::orderBy('first_name')->get();
        $doctors = User::where('role', 'doctor')->orderBy('name')->get();

        return view('appointments.index', compact('appointments', 'patients', 'doctors'));
    }

    /**
     * Show the form for creating a new appointment.
     */
    public function create()
    {
        $patients = Patient::orderBy('first_name')->get();
        $doctors = User::where('role', 'doctor')->orderBy('name')->get();
        return view('appointments.create', compact('patients', 'doctors'));
    }

    /**
     * Store a newly created appointment.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'doctor_id' => 'required|exists:users,id',
            'appointment_date' => 'required|date|after_or_equal:today',
            'time_slot' => 'required|date_format:H:i',
            'notes' => 'nullable|string',
        ]);

        // Check for duplicate appointment (same doctor, same date, same time)
        $exists = Appointment::where('doctor_id', $validated['doctor_id'])
            ->whereDate('appointment_date', $validated['appointment_date'])
            ->whereTime('time_slot', $validated['time_slot'])
            ->whereNotIn('status', ['cancelled'])
            ->exists();

        if ($exists) {
            return back()->withErrors(['time_slot' => 'This time slot is already booked for this doctor.']);
        }

        $validated['status'] = 'pending';

        Appointment::create($validated);

        return redirect()->route('appointments.index')
            ->with('success', 'Appointment booked successfully!');
    }

    /**
     * Display the specified appointment.
     */
    public function show(Appointment $appointment)
    {
        $appointment->load(['patient', 'doctor']);
        return view('appointments.show', compact('appointment'));
    }

    /**
     * Show the form for editing the appointment.
     */
    public function edit(Appointment $appointment)
    {
        // Only reception/admin can edit; doctor cannot change details but can update status via separate action
        if (auth()->user()->role === 'doctor') {
            abort(403, 'Doctors cannot edit appointments.');
        }

        $patients = Patient::orderBy('first_name')->get();
        $doctors = User::where('role', 'doctor')->orderBy('name')->get();
        return view('appointments.edit', compact('appointment', 'patients', 'doctors'));
    }

    /**
     * Update the appointment.
     */
    public function update(Request $request, Appointment $appointment)
    {
        if (auth()->user()->role === 'doctor') {
            abort(403);
        }

        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'doctor_id' => 'required|exists:users,id',
            'appointment_date' => 'required|date|after_or_equal:today',
            'time_slot' => 'required|date_format:H:i',
            'status' => ['required', Rule::in(['pending', 'confirmed', 'waiting', 'in-consultation', 'completed', 'cancelled'])],
            'notes' => 'nullable|string',
        ]);

        $appointment->update($validated);

        return redirect()->route('appointments.index')
            ->with('success', 'Appointment updated!');
    }

    /**
     * Update only the status (for doctors or quick check-in/out).
     */
    public function updateStatus(Request $request, Appointment $appointment)
    {
        $request->validate([
            'status' => ['required', Rule::in(['waiting', 'in-consultation', 'completed', 'cancelled'])],
        ]);

        $appointment->update(['status' => $request->status]);

        return redirect()->back()->with('success', 'Status updated!');
    }

    /**
     * Remove the appointment (soft delete or hard delete? We'll use soft delete via model).
     */
    public function destroy(Appointment $appointment)
    {
        $appointment->delete();
        return redirect()->route('appointments.index')
            ->with('success', 'Appointment cancelled.');
    }
}