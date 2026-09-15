<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Medicine extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'generic_name',
        'category',
        'unit',
        'purchase_price',
        'selling_price',
        'current_stock',
        'reorder_level',
        'expiry_date',
        'batch_no',
        'supplier_id',
    ];

    protected $casts = [
        'expiry_date' => 'date',
        'purchase_price' => 'decimal:2',
        'selling_price' => 'decimal:2',
    ];

    // Relationships
    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function stockMovements()
    {
        return $this->hasMany(StockMovement::class);
    }

    public function prescriptionItems()
    {
        return $this->hasMany(PrescriptionItem::class);
    }

    // Helper methods
    public function isLowStock(): bool
    {
        return $this->current_stock <= $this->reorder_level;
    }

    public function isExpired(): bool
    {
        return $this->expiry_date && $this->expiry_date->isPast();
    }

    public function getStockStatusAttribute(): string
    {
        if ($this->current_stock <= 0) return 'out-of-stock';
        if ($this->isLowStock()) return 'low-stock';
        if ($this->isExpired()) return 'expired';
        return 'in-stock';
    }
}