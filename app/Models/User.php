<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * Available user roles (for use in seeds/views)
     */
    const ROLES = ['admin', 'doctor', 'receptionist'];

    /**
     * Check if user has a specific role.
     */
    public function hasRole(string $role): bool
    {
        return $this->role === $role;
    }

    /**
     * Check if user is admin.
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Check if user is doctor.
     */
    public function isDoctor(): bool
    {
        return $this->role === 'doctor';
    }

    /**
     * Check if user is receptionist.
     */
    public function isReceptionist(): bool
    {
        return $this->role === 'receptionist';
    }

    // ==================== RELATIONSHIPS ====================

    /**
     * A doctor can have many appointments.
     */
    public function appointments()
    {
        return $this->hasMany(Appointment::class, 'doctor_id');
    }

    /**
     * A doctor can have many prescriptions.
     */
    public function prescriptions()
    {
        return $this->hasMany(Prescription::class, 'doctor_id');
    }

    /**
     * A user can perform many stock movements.
     */
    public function stockMovements()
    {
        return $this->hasMany(StockMovement::class);
    }

    // ==================== SCOPES ====================

    /**
     * Scope to get only doctors.
     */
    public function scopeDoctors($query)
    {
        return $query->where('role', 'doctor');
    }

    /**
     * Scope to get only receptionists.
     */
    public function scopeReceptionists($query)
    {
        return $query->where('role', 'receptionist');
    }

    /**
     * Scope to get only admins.
     */
    public function scopeAdmins($query)
    {
        return $query->where('role', 'admin');
    }
}