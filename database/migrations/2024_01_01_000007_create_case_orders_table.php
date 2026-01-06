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
        Schema::create('case_orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('court_case_id')->constrained()->cascadeOnDelete();
            $table->foreignId('judge_id')->constrained('users');
            $table->date('order_date');
            $table->string('type')->default('Interim'); // Interim, Final
            $table->longText('content');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('case_orders');
    }
};
