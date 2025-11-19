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
        Schema::create('sensors', function (Blueprint $table) {
            $table->id();
            $table->float('temperature')->nullable(); // Suhu (°C)
            $table->float('humidity')->nullable();    // Kelembaban (%)
            $table->float('nh3')->nullable();         // Amonia (ppm)
            $table->float('co')->nullable();          // Karbon monoksida (ppm)
            $table->float('cahaya')->nullable();      // Intensitas cahaya (lux)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sensors');
    }
};
