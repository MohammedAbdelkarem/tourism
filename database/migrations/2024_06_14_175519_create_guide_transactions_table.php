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
        Schema::create('guide_transactions', function (Blueprint $table) {
            $table->id();
            $table->integer('wallet');
            $table->date('date');
            $table->integer('amount');
            $table->foreignId('guide_id')->constrained('guides')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('guide_transactions');
    }
};
