<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Mpu6050;

class Mpu6050Controller extends Controller
{
    public function detail_prilaku()
    {
        // Ambil semua data perilaku sapi dan tampilkan dalam tabel
        $mpuData = Mpu6050::latest()->paginate(10);
        return view('detail_prilaku', compact('mpuData'));
    }

    public function destroy($id)
    {
        $data = Mpu6050::findOrFail($id);
        $data->delete();

        return redirect()->back()->with('success', 'Data berhasil dihapus.');
    }

    public function insert(Request $request)
    {
        $validated = $request->validate([
            'accel_x' => 'required|numeric',
            'accel_y' => 'required|numeric',
            'accel_z' => 'required|numeric',
            'gyro_x'  => 'required|numeric',
            'gyro_y'  => 'required|numeric',
            'gyro_z'  => 'required|numeric',
            'temperature' => 'required|numeric',
        ]);

        Mpu6050::create($validated);

        return redirect()->back()->with('success', 'Data perilaku sapi berhasil ditambahkan!');
    }


    public function store(Request $request)
    {
        Mpu6050::create([
            'accel_x'     => $request->input('accel_x'),
            'accel_y'     => $request->input('accel_y'),
            'accel_z'     => $request->input('accel_z'),
            'gyro_x'      => $request->input('gyro_x'),
            'gyro_y'      => $request->input('gyro_y'),
            'gyro_z'      => $request->input('gyro_z'),
            'temperature' => $request->input('temperature'),
        ]);

        return response()->json(['status' => 'ok']);
    }

    public function prilaku()
    {
        $latest = Mpu6050::latest()->first();

        if (!$latest) {
            return response()->json([
                'status_aktivitas' => '--',
                'prilaku'          => '--',
                'temperature'      => '--',
            ]);
        }

        // Hitung nilai aktivitas dari accelerometer dan gyroscope
        $accel = abs($latest->accel_x) + abs($latest->accel_y) + abs($latest->accel_z);
        $gyro  = abs($latest->gyro_x) + abs($latest->gyro_y) + abs($latest->gyro_z);

        // Logika sederhana untuk deteksi perilaku
        if ($accel < 0.2 && $gyro < 1) {
            $status  = "Diam";
            $prilaku = "Istirahat";
        } elseif ($accel > 0.5 && $gyro > 20) {
            $status  = "Aktif";
            $prilaku = "Berjalan / Makan";
        } else {
            $status  = "Aktif";
            $prilaku = "Bergerak Ringan";
        }

        return response()->json([
            'status_aktivitas' => $status,
            'prilaku'          => $prilaku,
            'temperature'      => $latest->temperature,
        ]);
    }
}
