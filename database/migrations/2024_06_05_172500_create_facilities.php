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
            $table->integer('number_of_places_available');
            $table->integer('price_per_person');
            $table->integer('profits')->default(0);
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
