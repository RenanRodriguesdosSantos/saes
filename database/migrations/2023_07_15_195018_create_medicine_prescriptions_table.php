<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('medicine_prescriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('medicine_id')->constrained();
            $table->foreignId('prescription_id')->constrained();
            $table->char('medicine_apresentation', 2)->nullable();
            $table->string('doctor_note')->nullable();
            $table->string('technician_note')->nullable();
            $table->unsignedSmallInteger('amount');
            $table->unsignedTinyInteger('status');
            $table->foreignId('technician_id')->nullable()->constrained('users');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('medicine_prescriptions');
    }
};
