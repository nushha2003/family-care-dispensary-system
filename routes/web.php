<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\PrescriptionController;
use App\Http\Controllers\DispenseController;
use App\Http\Controllers\ReceiptController;
use App\Http\Controllers\MedicineController;
use App\Http\Controllers\ReportController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// ==================== PUBLIC ROUTES ====================
Route::get('/', function () {
    return view('landing');
})->name('landing');

// ==================== AUTHENTICATED ROUTES (all roles) ====================
Route::middleware(['auth', 'verified'])->group(function () {

    // Dashboard - redirect to role-specific dashboard
    Route::get('/dashboard', function () {
        $role = auth()->user()->role;
        return redirect()->route($role . '.dashboard');
    })->name('dashboard');

    // Profile routes (provided by Breeze)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // ========== RESOURCE ROUTES (common for all authenticated users) ==========
    // Patients - all roles can view/manage (we'll add policies later if needed)
    Route::resource('patients', PatientController::class);

    // Appointments - all roles can view, but status update is for doctors
    Route::resource('appointments', AppointmentController::class);
    Route::patch('/appointments/{appointment}/status', [AppointmentController::class, 'updateStatus'])
        ->name('appointments.updateStatus');

    // Prescriptions - all roles can view, but create/edit limited by views
    Route::resource('prescriptions', PrescriptionController::class);

    // Dispense - receptionist only, but we let the controller logic handle authorization
    Route::get('/dispense/{prescription}', [DispenseController::class, 'create'])->name('dispense.create');
    Route::post('/dispense/{prescription}', [DispenseController::class, 'store'])->name('dispense.store');

    // Receipt PDF download (dispensed prescriptions only)
    Route::get('/receipt/{prescription}', [ReceiptController::class, 'generateReceipt'])
        ->name('receipt.generate');

    // Medicines - Admin only (we'll restrict via middleware/controller)
    Route::resource('medicines', MedicineController::class);
    Route::post('/medicines/{medicine}/adjust-stock', [MedicineController::class, 'adjustStock'])
        ->name('medicines.adjustStock');

           Route::get('/reports/daily',[ReportController::class,'dailyRevenue'])->name('reports.daily');
     Route::get('/reports/weekly',[ReportController::class,'weeklyRevenue'])->name('reports.weekly');
});

// ==================== ROLE-SPECIFIC DASHBOARDS ====================
// These routes must be placed after the auth middleware to ensure user is logged in.

Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('dashboard');
});

Route::middleware(['auth', 'role:doctor'])->prefix('doctor')->name('doctor.')->group(function () {
    Route::get('/dashboard', function () {
        return view('doctor.dashboard');
    })->name('dashboard');
});

Route::middleware(['auth', 'role:receptionist'])->prefix('receptionist')->name('receptionist.')->group(function () {
    Route::get('/dashboard', function () {
        return view('reception.dashboard');
    })->name('dashboard');

 
});

// ==================== AUTH ROUTES (BREEZE) ====================
require __DIR__.'/auth.php';