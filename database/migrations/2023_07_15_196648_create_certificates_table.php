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
        Schema::create('certificates', function (Blueprint $table) {
            $table->id();
            $table->unsignedTinyInteger('type');
            $table->unsignedTinyInteger('activity')->nullable();
            $table->dateTime('start_at')->nullable();
            $table->dateTime('end_at')->nullable();
            $table->boolean('show_cids')->default(false);
            $table->unsignedSmallInteger('duration')->nullable();
            $table->unsignedTinyInteger('duration_type')->nullable();
            $table->foreignId('doctor_id')->constrained('users');
            $table->foreignId('appointment_id')->constrained();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('certificates');
    }
};
