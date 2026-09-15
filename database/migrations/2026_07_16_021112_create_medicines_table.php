<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('medicines', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('generic_name')->nullable();
            $table->string('category')->nullable();
            $table->string('unit'); // e.g., tablet, capsule, ml, vial
            $table->decimal('purchase_price', 10, 2)->default(0);
            $table->decimal('selling_price', 10, 2);
            $table->integer('current_stock')->default(0);
            $table->integer('reorder_level')->default(10);
            $table->date('expiry_date')->nullable();
            $table->string('batch_no')->nullable();
            $table->foreignId('supplier_id')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('medicines');
    }
};