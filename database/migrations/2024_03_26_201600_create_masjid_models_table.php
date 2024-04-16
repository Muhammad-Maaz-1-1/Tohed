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
        Schema::create('masjid_models', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('masjid_name');
            $table->string('masjid_address');
            $table->string('city');
            $table->string('country');
            $table->string('imam_name');
            $table->string('khateeb_name')->nullable();
            $table->text('images');
            $table->string('contact_number')->nullable();
            $table->text('content')->nullable();
            $table->boolean('status')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('masjid_models');
    }
};
