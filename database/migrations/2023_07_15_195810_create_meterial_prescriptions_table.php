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
        Schema::create('material_prescriptions', function (Blueprint $table) {
            $table->id();

            $table->foreignId('material_id')->constrained();
            $table->foreignId('prescription_id')->constrained();
            $table->foreignId('technician_id')->nullable()->constrained('users');
            $table->unsignedSmallInteger('amount');
            $table->string('note')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('material_prescriptions');
    }
};
