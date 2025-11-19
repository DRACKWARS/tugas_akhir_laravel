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
        Schema::create('gy87_data', function (Blueprint $table) {
            $table->id();

            // ====== MPU6050 ======
            $table->float('accel_x')->nullable();   // percepatan sumbu X
            $table->float('accel_y')->nullable();   // percepatan sumbu Y
            $table->float('accel_z')->nullable();   // percepatan sumbu Z
            $table->float('gyro_x')->nullable();    // kecepatan sudut X
            $table->float('gyro_y')->nullable();    // kecepatan sudut Y
            $table->float('gyro_z')->nullable();    // kecepatan sudut Z
            $table->float('temperature')->nullable(); // suhu dari MPU6050

            // ====== HMC5883L (Kompas / Magnetometer) ======
            $table->float('mag_x')->nullable();     // medan magnet sumbu X
            $table->float('mag_y')->nullable();     // medan magnet sumbu Y
            $table->float('mag_z')->nullable();     // medan magnet sumbu Z

            // ====== BMP180 (Tekanan & Suhu Udara) ======
            $table->float('bmp_temp')->nullable();  // suhu dari BMP180
            $table->float('pressure')->nullable();  // tekanan udara (Pa)

            $table->timestamps(); // created_at & updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gy87_data');
    }
};
