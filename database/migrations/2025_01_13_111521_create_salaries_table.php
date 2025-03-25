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
        Schema::create('salaries', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('employee_id');
            $table->decimal('salary_base', 10, 2);
            $table->decimal('allowance', 10, 2)->nullable();
            $table->decimal('bonus', 10, 2)->nullable();
            $table->decimal('deduction', 10, 2)->nullable();
            $table->decimal('total_work')->nullable();
            $table->integer('total_late_arrivals')->nullable();
            $table->decimal('total_late_hours')->nullable();
            $table->string('description')->nullable();
            $table->date('base_salary');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('salaries');
    }
};
