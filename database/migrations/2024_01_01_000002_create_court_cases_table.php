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
        Schema::create('court_cases', function (Blueprint $table) {
            $table->id();
            $table->string('case_number')->unique()->nullable();
            $table->year('year')->nullable();
            $table->foreignId('case_status_id')->constrained();
            $table->foreignId('filed_by')->constrained('users');
            $table->foreignId('assigned_judge_id')->nullable()->constrained('users');
            // $table->foreignId('court_id')->constrained(); // Assuming court table exists later
            $table->string('title')->nullable();
            $table->text('description')->nullable();
            $table->date('filing_date')->nullable();
            $table->date('next_hearing_date')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('court_cases');
    }
};
