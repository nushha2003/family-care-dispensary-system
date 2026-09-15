<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Appointment extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'patient_id',
        'doctor_id',
        'appointment_date',
        'time_slot',
        'status',
        'notes',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'appointment_date' => 'date',
        'time_slot' => 'datetime:H:i', // Cast to time format
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    /**
     * Get the patient for this appointment.
     */
    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    /**
     * Get the doctor (user) for this appointment.
     */
    public function doctor()
    {
        return $this->belongsTo(User::class, 'doctor_id');
    }

    /**
     * A scope to get today's appointments.
     */
    public function scopeToday($query)
    {
        return $query->whereDate('appointment_date', now()->toDateString());
    }

    /**
     * A scope to get upcoming appointments (from today onwards).
     */
    public function scopeUpcoming($query)
    {
        return $query->whereDate('appointment_date', '>=', now()->toDateString());
    }

    /**
     * A scope to filter by status.
     */
    public function scopeWithStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * A scope to filter by doctor.
     */
    public function scopeForDoctor($query, $doctorId)
    {
        return $query->where('doctor_id', $doctorId);
    }

    /**
     * Check if the appointment can be started (moved to in-consultation).
     */
    public function canStart(): bool
    {
        return in_array($this->status, ['pending', 'confirmed', 'waiting']);
    }

    /**
     * Check if the appointment can be completed.
     */
    public function canComplete(): bool
    {
        return $this->status === 'in-consultation';
    }

    /**
     * Check if the appointment can be cancelled.
     */
    public function canCancel(): bool
    {
        return !in_array($this->status, ['completed', 'cancelled']);
    }

    /**
     * Get the formatted time slot (e.g., 09:00 AM).
     */
    public function getFormattedTimeAttribute(): string
    {
        return $this->time_slot->format('h:i A');
    }

    /**
     * Get the status with a badge color.
     */
    public function getStatusBadgeAttribute(): string
    {
        $colors = [
            'pending' => 'secondary',
            'confirmed' => 'info',
            'waiting' => 'warning',
            'in-consultation' => 'primary',
            'completed' => 'success',
            'cancelled' => 'danger',
        ];

        return $colors[$this->status] ?? 'secondary';
    }

    /**
     * Get the human-readable status label.
     */
    public function getStatusLabelAttribute(): string
    {
        return ucfirst(str_replace('-', ' ', $this->status));
    }
}