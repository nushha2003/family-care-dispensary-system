<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Prescription extends Model
{
    use HasFactory;

    protected $fillable = [
        'prescription_number',
        'patient_id',
        'doctor_id',
        'appointment_id',
        'issued_date',
        'valid_until',
        'notes',
        'is_dispensed',
    ];

    protected $casts = [
        'issued_date' => 'date',
        'valid_until' => 'date',
        'is_dispensed' => 'boolean',
    ];

    // Relationships
    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function doctor()
    {
        return $this->belongsTo(User::class, 'doctor_id');
    }

    public function appointment()
    {
        return $this->belongsTo(Appointment::class);
    }

    public function items()
    {
        return $this->hasMany(PrescriptionItem::class);
    }

    // Auto-generate prescription number
    protected static function boot()
    {
        parent::boot();
        static::creating(function ($prescription) {
            if (empty($prescription->prescription_number)) {
                $prescription->prescription_number = 'RX-' . date('Ymd') . '-' . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);
            }
        });
    }
}