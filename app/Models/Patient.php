<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Patient extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'dob',
        'gender',
        'phone',
        'email',
        'address',
        'allergies',
        'chronic_conditions',
        'medical_history',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'dob' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    /**
     * Get the patient's full name.
     */
    public function getFullNameAttribute(): string
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    /**
     * Get the patient's age.
     */
    public function getAgeAttribute(): int
    {
        return $this->dob->age;
    }

    /**
     * Scope a query to search by name or phone.
     */
    public function scopeSearch($query, $term)
    {
        return $query->where(function ($q) use ($term) {
            $q->where('first_name', 'LIKE', "%{$term}%")
              ->orWhere('last_name', 'LIKE', "%{$term}%")
              ->orWhere('phone', 'LIKE', "%{$term}%")
              ->orWhere('email', 'LIKE', "%{$term}%");
        });
    }

    // ==================== RELATIONSHIPS ====================

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }

    /**
     * A patient can have many prescriptions.
     */
    public function prescriptions()
    {
        return $this->hasMany(Prescription::class);
    }
}