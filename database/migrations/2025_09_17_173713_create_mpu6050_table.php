<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('mpu6050', function (Blueprint $table) {
            $table->id();
            $table->float('accel_x')->nullable(); // percepatan sumbu X
            $table->float('accel_y')->nullable(); // percepatan sumbu Y
            $table->float('accel_z')->nullable(); // percepatan sumbu Z
            $table->float('gyro_x')->nullable();  // kecepatan sudut sumbu X
            $table->float('gyro_y')->nullable();  // kecepatan sudut sumbu Y
            $table->float('gyro_z')->nullable();  // kecepatan sudut sumbu Z
            $table->float('temperature')->nullable(); // suhu internal sensor
            $table->timestamps(); // created_at & updated_at
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mpu6050');
    }
};
