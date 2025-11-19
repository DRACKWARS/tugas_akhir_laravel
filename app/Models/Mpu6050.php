<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mpu6050 extends Model
{
    use HasFactory;

    protected $table = 'mpu6050';

    protected $fillable = [
        'accel_x',
        'accel_y',
        'accel_z',
        'gyro_x',
        'gyro_y',
        'gyro_z',
        'temperature',
    ];

}
