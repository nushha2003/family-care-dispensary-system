<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PrescriptionItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'prescription_id',
        'medicine_id',
        'dosage',
        'frequency',
        'duration',
        'quantity',
        'instructions',
    ];

    // Relationships
    public function prescription()
    {
        return $this->belongsTo(Prescription::class);
    }

    public function medicine()
    {
        return $this->belongsTo(Medicine::class);
    }

    // Helper: Get total cost of this line item
    public function getSubtotalAttribute()
    {
        return $this->quantity * $this->medicine->selling_price;
    }
}