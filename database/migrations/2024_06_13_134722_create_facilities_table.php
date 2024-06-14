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
        Schema::create('facilities', function (Blueprint $table) {
            $table->id();
            $table->enum('type' , ['Restaurant' , 'Hotel' , 'Place'])->default('Place');
            $table->string('name');
            $table->string('photo')->nullable();
            $table->double('lat');
            $table->double('long');
            $table->string('bio');
            $table->integer('number_of_available_places');
            $table->integer('price_per_person');
            $table->integer('profits');
            $table->foreignId('country_id')->constrained('countries')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('facilities');
    }
};
