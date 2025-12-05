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
Route::post('/insert_mpu', [Mpu6050Controller::class, 'insert'])->name('insert_mpu');


// ✅ API untuk sensor
Route::post('/sensor', [SensorController::class, 'store']);
Route::get('/sensor/latest', [SensorController::class, 'latest']);
Route::get('/api/sensor-all', function () {
    return \App\Models\Sensor::orderBy('id', )->get();
});

// ✅ API untuk perilaku sapi (MPU6050)
Route::get('/detail_prilaku', [Mpu6050Controller::class, 'detail_prilaku'])->name('detail_prilaku');
Route::post('/mpu6050', [Mpu6050Controller::class, 'store']);
Route::get('/sensor/prilaku', [Mpu6050Controller::class, 'prilaku']);
Route::get('/delete_mpu/{id}', [Mpu6050Controller::class, 'delete_prilaku'])->name('delete_mpu');
Route::get('/mpu/export-json', function () {
    return \App\Models\Mpu6050::orderBy('id', 'asc')->get();
});

// ✅ API untuk perilaku sapi (GY-87)
Route::post('/gy87', [Gy87Controller::class, 'store']);
Route::get('/gy87/latest', [Gy87Controller::class, 'latest']);
Route::get('/detail_prilaku2', [Gy87Controller::class, 'detail_prilaku2'])->name('detail_prilaku2');
Route::get('/gy87/prilaku', [Gy87Controller::class, 'prilaku']);
Route::get('/delete_prilaku/{id}', [Gy87Controller::class, 'delete_prilaku']);
Route::post('/insert_prilaku', [Gy87Controller::class, 'insert_prilaku']);
Route::get('/api/gy87-all', function() {
    return \App\Models\Gy87::orderBy('id','ASC')->get();
});
