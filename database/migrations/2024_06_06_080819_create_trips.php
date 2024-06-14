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
            $table->integer('rate')->nullable();
            $table->integer('price_per_one_old')->default(0);
            $table->integer('price_per_one_new')->default(0);
            $table->integer('total_price')->default(0);
            $table->enum('status' , ['pending' , 'active' , 'finished'])->default('pending');
            $table->date('start_date');
            $table->date('end_date');
            $table->integer('number_of_filled_places')->default(0);
            $table->integer('number_of_available_places')->default(0);
            $table->integer('number_of_original_places')->default(0);
            $table->integer('offer_ratio')->default(0);
            $table->double('lat')->nullable();
            $table->double('long')->nullable();
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
