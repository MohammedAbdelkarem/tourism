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
            $table->float('rate')->default(0);
            $table->integer('price_per_one_old');
            $table->enum('status',['available' , 'unavailable'])->default('available');
            $table->integer('price_per_person_new')->default(0);
            $table->integer('offers_ratio')->default(0);
            $table->integer('total_price');
            $table->string('first_date');
            $table->string('end_date');
            $table->integer('num_of_person');
            $table->integer('num_of_places');
            $table->string('photo')->nullable();
            $table->unsignedBigInteger('country_id');
            $table->foreign('country_id')->references('id')->on('countries')->cascadeOnDelete();
            $table->unsignedBigInteger('guide_id');
            $table->foreign('guide_id')->references('id')->on('guides')->cascadeOnDelete();
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
