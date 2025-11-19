<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SensorController;
use App\Http\Controllers\Mpu6050Controller;
use App\Http\Controllers\Gy87Controller;

// ✅ Tampilan
Route::get('/', [SensorController::class, 'index']);
Route::get('/detail_lingkungan', [SensorController::class, 'detail_lingkungan']);
Route::get('/detail_thi', [SensorController::class, 'detail_thi']);
Route::get('/sensor/all', [SensorController::class, 'getAllData']);
Route::post('/insert_sensor', [SensorController::class, 'insert'])->name('insert_sensor');
Route::delete('/sensor/{id}', [SensorController::class, 'destroy'])->name('sensor.destroy');
Route::get('/detail_prilaku', [Mpu6050Controller::class, 'detail_prilaku'])->name('detail_prilaku');
Route::post('/insert_mpu', [Mpu6050Controller::class, 'insert'])->name('insert_mpu');
Route::delete('/mpu/{id}', [Mpu6050Controller::class, 'destroy']);

// ✅ API untuk sensor
Route::post('/sensor', [SensorController::class, 'store']);
Route::get('/sensor/latest', [SensorController::class, 'latest']);

// ✅ API untuk perilaku sapi (MPU6050)
Route::post('/mpu6050', [Mpu6050Controller::class, 'store']);
Route::get('/sensor/prilaku', [Mpu6050Controller::class, 'prilaku']);

// ✅ API untuk perilaku sapi (GY-87)
Route::post('/gy87', [Gy87Controller::class, 'store']);
Route::get('/gy87/latest', [Gy87Controller::class, 'latest']);
Route::get('/detail_prilaku2', [Gy87Controller::class, 'detail_prilaku2'])->name('detail_prilaku2');
Route::get('/gy87/prilaku', [Gy87Controller::class, 'prilaku']);
