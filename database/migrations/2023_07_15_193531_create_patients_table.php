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
        Schema::create('patients', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('mother');
            $table->string('father')->nullable();
            $table->date('birth_date');
            $table->char('gender');
            $table->string('cpf',14)->nullable();
            $table->string('cns')->nullable();
            $table->string('phone')->nullable();
            $table->string('rg')->nullable();
            $table->string('profession')->nullable();
            $table->string('neighborhood');
            $table->string('place');
            $table->string('residence_number')->nullable();
            $table->string('complement')->nullable();

            $table->foreignId('county_id')->nullable()->constrained();
            $table->foreignId('naturalness_id')->nullable()->constrained('counties');
            $table->foreignId('ethnicity_id')->nullable()->constrained();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('patients');
    }
};
