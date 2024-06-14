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
        Schema::create('guides', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('phone')->unique();
            $table->string('password');
            $table->enum('status',['available' , 'unavailable'])->default('available');
            $table->integer('price_per_person_one_day');
            $table->string('father_name');
            $table->string('mother_name');
            $table->string('unique_id')->unique();
            $table->string('birth_place');
            $table->string('birth_date');
            $table->string('photo')->nullable();
            $table->enum('can_change_unique_id' , ['able' , 'unable'])->nullable();
            $table->enum('accept_by_admin' , ['accepted' , 'rejected'])->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('guides');
    }
};
