<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockMovement extends Model
{
    use HasFactory;

    protected $fillable = [
        'medicine_id',
        'user_id',
        'quantity_change',
        'movement_type',
        'reference_type',
        'reference_id',
        'note',
    ];

    // Relationships
    public function medicine()
    {
        return $this->belongsTo(Medicine::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Helper: Deduct stock (for dispensing)
    public static function deduct($medicineId, $quantity, $userId, $referenceType, $referenceId, $note = null)
    {
        $medicine = Medicine::findOrFail($medicineId);
       
        if ($medicine->current_stock < $quantity) {
            throw new \Exception("Insufficient stock for {$medicine->name}. Available: {$medicine->current_stock}");
        }

        $medicine->decrement('current_stock', $quantity);

        return self::create([
            'medicine_id' => $medicineId,
            'user_id' => $userId,
            'quantity_change' => -$quantity,
            'movement_type' => 'sale',
            'reference_type' => $referenceType,
            'reference_id' => $referenceId,
            'note' => $note,
        ]);
    }

    // Helper: Add stock (for purchases)
    public static function add($medicineId, $quantity, $userId, $note = null)
    {
        $medicine = Medicine::findOrFail($medicineId);
        $medicine->increment('current_stock', $quantity);

        return self::create([
            'medicine_id' => $medicineId,
            'user_id' => $userId,
            'quantity_change' => $quantity,
            'movement_type' => 'purchase',
            'note' => $note,
        ]);
    }
}