<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sensor;
use App\Models\Mpu6050;
use Carbon\Carbon;

class SensorController extends Controller
{
    public function index()
    {
        $sensors = Sensor::latest()->first(); // ambil data terbaru
        return view('home', compact('sensors'));
    }

    public function detail_lingkungan()
    {
        $sensors = Sensor::all();
        $sensors = Sensor::latest()->paginate(10);
        return view('detail_lingkungan', compact('sensors'));
    }

    
    public function detail_thi()
    {
        return view('detail_thi');
    }

    public function getAllData()
    {
        $data = \App\Models\Sensor::orderBy('created_at', 'desc')->get();

        $formatted = $data->map(function ($row) {
            return [
                'id' => $row->id,
                'temperature' => $row->temperature,
                'humidity' => $row->humidity,
                'nh3' => $row->nh3,
                'co' => $row->co,
                'cahaya' => $row->cahaya,
                'created_at' => Carbon::parse($row->created_at)
                    ->timezone('Asia/Jakarta')
                    ->format('d-m-Y H:i:s'),
            ];
        });

        return response()->json($formatted);
    }



    public function store(Request $request)
    {
        Sensor::create([
            'temperature' => $request->input('temperature'),
            'humidity'    => $request->input('humidity'),
            'nh3'         => $request->input('nh3'),
            'co'         => $request->input('co'),
            'cahaya'      => $request->input('cahaya'),
        ]);

        return response()->json(['status' => 'ok']);
    }

    public function latest()
    {
        $sensor = Sensor::latest()->first();

        if (!$sensor) {
            return response()->json([
                'time'        => now()->format('H:i'),
                'status'      => 'Belum ada data',
                'nh3'         => '--',
                'co'         => '--',
                'temperature' => '--',
                'humidity'    => '--',
                'cahaya'      => '--',
            ]);
        }

        // Atur status otomatis
        $status = 'Baik';
        if ($sensor->nh3 > 50) {
            $status = 'Bahaya';
        } elseif ($sensor->temperature > 35) {
            $status = 'Panas';
        }

        return response()->json([
            'time'        => $sensor->created_at->format('H:i'),
            'status'      => $status,
            'nh3'         => $sensor->nh3,
            'co'         => $sensor->co,
            'temperature' => $sensor->temperature,
            'humidity'    => $sensor->humidity,
            'cahaya'      => $sensor->cahaya,
        ]);
    }

    public function insert(Request $request)
    {
        $validated = $request->validate([
            'nh3' => 'required|numeric',
            'co' => 'required|numeric',
            'temperature' => 'required|numeric',
            'humidity' => 'required|numeric',
            'cahaya' => 'required|numeric',
    ]);

        Sensor::create($validated);

        return redirect()->back()->with('success', 'Data sensor berhasil ditambahkan!');
    }

    public function destroy($id)
    {
        $sensor = Sensor::findOrFail($id);
        $sensor->delete();

        return redirect()->back()->with('success', 'Data sensor berhasil dihapus!');
    }
}
