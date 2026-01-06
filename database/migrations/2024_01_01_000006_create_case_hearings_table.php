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
        Schema::create('case_hearings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('court_case_id')->constrained()->cascadeOnDelete();
            // $table->foreignId('court_id')->constrained(); // Future
            $table->date('hearing_date');
            $table->time('hearing_time')->nullable();
            $table->string('purpose'); // e.g., "First Hearing", "Witness", "Verdict"
            $table->text('notes')->nullable();
            $table->string('status')->default('scheduled'); // scheduled, completed, adjourned
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('case_hearings');
    }
};
