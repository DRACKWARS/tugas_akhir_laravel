<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gy87 extends Model
{
    use HasFactory;

    protected $table = 'gy87_data';

    protected $fillable = [
        'accel_x',
        'accel_y',
        'accel_z',
        'gyro_x',
        'gyro_y',
        'gyro_z',
        'temperature',
        'mag_x',
        'mag_y',
        'mag_z',
        'bmp_temp',
        'pressure',
    ];
}
