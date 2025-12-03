<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Gy87;

class Gy87Controller extends Controller
{

    public function detail_prilaku2()
    {
        // Ambil semua data perilaku sapi dan tampilkan dalam tabel
        $gy87 = Gy87::latest()->paginate(10);
        return view('detail_prilaku2', compact('gy87'));
    }

    public function delete_prilaku($id)
    {
        $data = Gy87::findOrFail($id);
        $data->delete();

        return redirect()->back()->with('success', 'Data berhasil dihapus.');
    }

    // ===== SIMPAN DATA DARI ESP8266 =====
    public function store(Request $request)
    {
        $data = Gy87::create([
            'accel_x'     => $request->input('accel_x'),
            'accel_y'     => $request->input('accel_y'),
            'accel_z'     => $request->input('accel_z'),
            'gyro_x'      => $request->input('gyro_x'),
            'gyro_y'      => $request->input('gyro_y'),
            'gyro_z'      => $request->input('gyro_z'),
            'temperature' => $request->input('temperature'),
            'mag_x'       => $request->input('mag_x'),
            'mag_y'       => $request->input('mag_y'),
            'mag_z'       => $request->input('mag_z'),
            'bmp_temp'    => $request->input('bmp_temp'),
            'pressure'    => $request->input('pressure'),
        ]);

        // return redirect()->back()->with('success', 'Data GY-87 berhasil ditambahkan!');
        return response()->json(['status' => 'ok', 'message' => 'Data GY-87 tersimpan!']);
    }

    public function insert_prilaku(Request $request)
    {
        Gy87::create([
            'accel_x'     => $request->accel_x,
            'accel_y'     => $request->accel_y,
            'accel_z'     => $request->accel_z,
            'gyro_x'      => $request->gyro_x,
            'gyro_y'      => $request->gyro_y,
            'gyro_z'      => $request->gyro_z,
            'mag_x'       => $request->mag_x,
            'mag_y'       => $request->mag_y,
            'mag_z'       => $request->mag_z,
            'temperature' => $request->temperature,
            'pressure'    => $request->pressure,
        ]);

        return redirect()->back()->with('success', 'Data GY-87 berhasil ditambahkan!');
    }


    // ===== AMBIL DATA TERBARU =====
    public function latest()
    {
        $latest = Gy87::latest()->first();

        if (!$latest) {
            return response()->json(['message' => 'Belum ada data sensor!']);
        }

        return response()->json($latest);
    }

    // ===== LOGIKA DETEKSI PERILAKU SAPI =====
    public function prilaku()
    {
        $latest = Gy87::latest()->first();

        if (!$latest) {
            return response()->json([
                'status_aktivitas' => '--',
                'prilaku'          => '--',
                'temperature'      => '--',
            ]);
        }

        // --- Hitung intensitas gerakan ---
        $accel_total = sqrt(
            pow($latest->accel_x, 2) +
            pow($latest->accel_y, 2) +
            pow($latest->accel_z, 2)
        );

        $gyro_total = sqrt(
            pow($latest->gyro_x, 2) +
            pow($latest->gyro_y, 2) +
            pow($latest->gyro_z, 2)
        );

        // --- Sudut orientasi sederhana dari magnetometer ---
        $heading = atan2($latest->mag_y, $latest->mag_x) * (180 / M_PI);
        if ($heading < 0) $heading += 360;

        // --- Logika perilaku berdasarkan ambang batas empiris ---
        $status = "";
        $prilaku = "";

        if ($accel_total < 0.15 && $gyro_total < 2) {
            $status = "Diam";
            $prilaku = "Istirahat / Berbaring";
        }
        elseif ($accel_total >= 0.15 && $accel_total < 0.5 && $gyro_total < 15) {
            $status = "Ringan";
            $prilaku = "Berdiri / Mengunyah";
        }
        elseif ($accel_total >= 0.5 && $gyro_total >= 15 && $gyro_total < 60) {
            $status = "Aktif";
            $prilaku = "Berjalan / Bergerak";
        }
        elseif ($gyro_total >= 60) {
            $status = "Sangat Aktif";
            $prilaku = "Gelisah / Berlari";
        }
        else {
            $status = "Tidak Teridentifikasi";
            $prilaku = "Data sensor tidak stabil";
        }

        // --- Kembalikan hasil ke frontend atau API ---
        return response()->json([
            'status_aktivitas' => $status,
            'prilaku'          => $prilaku,
            'temperature'      => $latest->temperature,
            'bmp_temp'         => $latest->bmp_temp,
            'pressure'         => $latest->pressure,
            'arah_gerak'       => round($heading, 2) . "°",
        ]);
    }
}
