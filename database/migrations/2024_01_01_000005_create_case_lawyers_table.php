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
        Schema::create('case_lawyers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('court_case_id')->constrained()->cascadeOnDelete();
            $table->foreignId('lawyer_id')->constrained('users');
            $table->foreignId('assigned_by')->nullable()->constrained('users'); // Who initiated
            $table->string('status')->default('pending'); // pending, accepted, rejected, removed
            $table->boolean('is_lead')->default(false);
            $table->timestamps();

            // Prevent duplicate assignments
            $table->unique(['court_case_id', 'lawyer_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('case_lawyers');
    }
};
