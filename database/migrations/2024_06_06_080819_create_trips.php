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
        Schema::create('trips', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('bio');
            $table->string('photo');
            $table->integer('rate');
            $table->integer('price_per_one_old');
            $table->integer('price_per_one_new');
            $table->integer('total_price');
            $table->enum('status' , ['pending' , 'active' , 'finished']);
            $table->date('start_date');
            $table->date('end_date');
            $table->integer('number_of_filled_places');
            $table->integer('number_of_available_places');
            $table->integer('number_of_original_places');
            $table->integer('offer_ratio');
            $table->foreignId('guide_id')->constrained('guides_backups')->cascadeOnDelete();
            $table->foreignId('country_id')->constrained('countries')->cascadeOnDelete();
            $table->timestamps();
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trips');
    }
};
