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
        Schema::create('guides_backups', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email');
            $table->string('phone');
            $table->string('password');
            $table->enum('status',['available' , 'unavailable'])->default('available');
            $table->integer('price_per_person_one_day');
            $table->string('father_name');
            $table->string('mother_name');
            $table->string('unique_id');
            $table->string('birth_place');
            $table->string('birth_date');
            $table->integer('wallet')->nullable();
            $table->string('photo')->nullable();
            $table->enum('can_change_unique_id' , ['able' , 'unable'])->default('unable');
            $table->enum('accept_by_admin' , ['accepted' , 'rejected'])->default('rejected');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('guides_backups');
    }
};
