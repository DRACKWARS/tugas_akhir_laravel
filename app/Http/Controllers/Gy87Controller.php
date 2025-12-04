<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Gy87;

class Gy87Controller extends Controller
{

    public function detail_prilaku2()
    {
        // Ambil data terbaru dengan pagination
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
        // Validasi input sederhana (opsional tapi disarankan)
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

        return redirect()->back()->with('success', 'Data GY-87 berhasil ditambahkan manually!');
    }

    // ===== AMBIL DATA TERBARU (RAW JSON) =====
    public function latest()
    {
        $latest = Gy87::latest()->first();

        if (!$latest) {
            return response()->json(['message' => 'Belum ada data sensor!'], 404);
        }

        return response()->json($latest);
    }

    // ===== LOGIKA DETEKSI PERILAKU SAPI (API) =====
    // Logika ini disamakan dengan Tampilan Blade agar konsisten
    public function prilaku()
    {
        $latest = Gy87::latest()->first();

        if (!$latest) {
            return response()->json([
                'status_aktivitas' => 'Menunggu Data...',
                'prilaku'          => '--',
                'temperature'      => '--',
                'bmp_temp'         => '--',
                'pressure'         => '--',
                'arah_gerak'       => '--',
            ]);
        }

        // --- 1. Hitung Magnitude (Total Vector) ---
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

        // --- 2. Hitung Compass Heading ---
        $heading = atan2($latest->mag_y, $latest->mag_x) * (180 / M_PI);
        if ($heading < 0) $heading += 360;

        // --- 3. Logika Klasifikasi Perilaku (UPDATED) ---
        // Menggunakan "Priority Check" dan operator "OR" (||)
        
        $status = "";
        $prilaku = "";

        // PRIORITAS 1: Gerakan Ekstrem (Gelisah/Lari)
        // Indikator utama: Gyroscope berputar cepat
        if ($gyro_total >= 60) {
            $status = "Sangat Aktif";
            $prilaku = "Gelisah / Berlari";
        }
        // PRIORITAS 2: Gerakan Aktif (Jalan/Makan)
        // Jika Accel cukup tinggi ATAU Gyro mendeteksi putaran tubuh
        elseif ($accel_total >= 0.4 || $gyro_total >= 15) {
            $status = "Aktif";
            $prilaku = "Berjalan / Bergerak";
        }
        // PRIORITAS 3: Gerakan Ringan (Mengunyah/Berdiri)
        // Ambang batas sensitif (0.08) untuk deteksi gerakan mikro
        elseif ($accel_total >= 0.08 || $gyro_total >= 2) {
            $status = "Ringan";
            $prilaku = "Berdiri / Mengunyah";
        }
        // PRIORITAS 4: Fallback (Diam)
        // Jika tidak memenuhi kriteria di atas, pasti Diam
        else {
            $status = "Diam";
            $prilaku = "Istirahat / Berbaring";
        }

        // --- Kembalikan hasil ke frontend atau API ---
        return response()->json([
            'status_aktivitas' => $status,
            'prilaku'          => $prilaku,
            'temperature'      => number_format($latest->temperature, 2),
            'bmp_temp'         => number_format($latest->bmp_temp, 2),
            'pressure'         => number_format($latest->pressure, 2),
            'arah_gerak'       => round($heading, 2) . "°",
            'raw_accel'        => number_format($accel_total, 2), // Debugging (opsional)
            'raw_gyro'         => number_format($gyro_total, 2)   // Debugging (opsional)
        ]);
    }
}