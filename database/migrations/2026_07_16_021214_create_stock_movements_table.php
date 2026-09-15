<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stock_movements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('medicine_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // who performed the movement
            $table->integer('quantity_change'); // positive = purchase, negative = sale/wastage
            $table->enum('movement_type', ['purchase', 'sale', 'adjustment', 'expiry_writeoff']);
            $table->string('reference_type')->nullable(); // e.g., 'prescription', 'purchase_order'
            $table->unsignedBigInteger('reference_id')->nullable();
            $table->text('note')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stock_movements');
    }
};