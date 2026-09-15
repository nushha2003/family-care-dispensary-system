<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('patients', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('last_name');
            $table->date('dob');
            $table->enum('gender', ['male', 'female', 'other']);
            $table->string('phone')->unique();
            $table->string('email')->nullable()->unique();
            $table->text('address')->nullable();
            $table->text('allergies')->nullable();          // e.g., "Penicillin, Aspirin"
            $table->text('chronic_conditions')->nullable(); // e.g., "Diabetes, Hypertension"
            $table->text('medical_history')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('patients');
    }
};