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
        Schema::create('rishta_models', function (Blueprint $table) {
            $table->id();
            $table->string('full_name');
            $table->enum('gender', ['میل', 'فی میل']);
            $table->date('birthdate');
            $table->string('marital_status'); // Changed to a string column
            $table->string('city');
            $table->string('country');
            $table->string('mother_tongue');
            $table->string('education');
            $table->string('profession');
            $table->string('ethnicity');
                $table->text('content')->nullable();
                $table->boolean('status')->default(false);
            $table->string('images')->nullable(); // For male uploads only
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rishta_models');
    }
};
