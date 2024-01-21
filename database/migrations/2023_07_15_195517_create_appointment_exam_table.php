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
        Schema::create('appointment_exam', function (Blueprint $table) {
            $table->id();
            $table->string('doctor_note')->nullable();
            $table->string('technician_note')->nullable();
            $table->foreignId('appointment_id')->constrained();
            $table->foreignId('exam_id')->constrained();
            $table->foreignId('doctor_id')->constrained('users');
            $table->foreignId('technician_id')->nullable()->constrained('users');
            $table->unique(['appointment_id', 'exam_id']);
            $table->unsignedTinyInteger('status');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('appointment_exam');
    }
};
