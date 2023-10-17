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
        Schema::create('vital_signs', function (Blueprint $table) {
            $table->id();
            $table->float('blood_glucose')->nullable();
            $table->unsignedInteger('heart_rate')->nullable();
            $table->float('saturation')->nullable();
            $table->float('temperature')->nullable();
            $table->string('blood_pressure', 8)->nullable();
            $table->float('weight')->nullable();
            $table->unsignedTinyInteger('glasgow')->nullable();

            $table->foreignId('service_id')->constrained();
            $table->foreignId('nurse_id')->constrained('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vital_signs');
    }
};
