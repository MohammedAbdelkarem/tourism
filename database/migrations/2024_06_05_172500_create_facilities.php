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
            $table->string('name')->nullable();
            $table->string('photo')->nullable();
            $table->enum('type', ['restaurant', 'hotel','place']);
            $table->string('lat');
            $table->string('long');
            $table->text('bio')->nullable();
            $table->integer('number_of_places');
            $table->integer('price_per_person');
            $table->integer('profits')->default(0);
            $table->float('rate')->default(0);
            $table->unsignedBigInteger('country_id'); // Define the country_id column
            $table->foreign('country_id')->references('id')->on('countries')->cascadeOnDelete();
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
