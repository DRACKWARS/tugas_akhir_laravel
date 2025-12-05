<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Sensor Lingkungan - Smart Cattle Monitor</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link href="{{ asset('assets/css/styleD.css') }}" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
</head>
<body>

    <!-- Loading Overlay -->
    <div class="loading-overlay" id="loadingOverlay">
        <div class="text-center">
            <div class="spinner mb-3"></div>
            <p style="color: var(--primary-green); font-weight: 600;">Memuat data sensor...</p>
        </div>
    </div>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark shadow-sm">
        <div class="container">
            <a class="navbar-brand fw-bold" href="/">
                <i class="fas fa-cow"></i> Pemantauan Ternak
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link text-white" href="/">
                            <i class="fas fa-home"></i> Home
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white active" href="#">
                            <i class="fas fa-chart-line"></i> Sensor Lingkungan
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Page Header -->
    <div class="page-header">
        <div class="container">
            <h1>
                <i class="fas fa-seedling"></i>
                Analisis Data Sensor Lingkungan
            </h1>
            <nav class="breadcrumb">
                <a href="/" class="text-white text-decoration-none">Home</a>
                <span class="mx-2">/</span>
                <span>Sensor Lingkungan</span>
            </nav>
        </div>
    </div>

    <div class="container my-5">
        <!-- Chart Section -->
        <div class="chart-container">
            <h4 class="mb-4">
                <i class="fas fa-chart-area text-success me-2"></i>
                Grafik Trend Sensor Lingkungan
            </h4>
            <canvas id="sensorChart" width="400" height="100"></canvas>
        </div>

        <!-- Info -->
        <div class="alert info-alert shadow-sm">
            <h6>
                <i class="fas fa-stethoscope me-2"></i>
                Konsentrasi PPM Amonia & Respon Kesehatan Ternak
            </h6>
            <ul class="mb-0">
                <li><b>0–25 ppm:</b> Aman — Tidak ada efek kesehatan pada sapi.</li>
                <li><b>26–50 ppm:</b> Waspada — Sapi mungkin mengalami iritasi ringan pada mata dan hidung.</li>
                <li><b>51–100 ppm:</b> Bahaya — Dapat menyebabkan batuk, stres, dan penurunan nafsu makan.</li>
                <li><b>>100 ppm:</b> Sangat Berbahaya — Risiko gangguan pernapasan dan penurunan produksi susu.</li>
            </ul>
        </div>

        <!-- Main Data Card -->
        <div class="main-card">
            <div class="card-body p-0">
                <div class="card-header-custom d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">
                        <i class="fas fa-database text-success me-2"></i>
                        Data Sensor Real-Time
                    </h4>
                    <div class="d-flex gap-2">
                        <button class="btn btn-add-data" data-bs-toggle="modal" data-bs-target="#addSensorModal">
                            <i class="fas fa-plus-circle me-2"></i> Tambah Data
                        </button>
                        <button class="btn btn-add-data" onclick="downloadExcel()">
                            <i class="fas fa-file-excel"></i> Export Excel
                        </button>
                    </div>
                </div>

                <div class="table-responsive">
                    <table id="tableLINGKUNGAN" class="table table-hover mb-0 data-table">
                        <thead>
                            <tr>
                                <th><i class="fas fa-hashtag me-2"></i>No</th>
                                <th><i class="fas fa-clock me-2"></i>Tanggal & Waktu</th>
                                <th><i class="fas fa-smog me-2"></i>NH₃ (ppm)</th>
                                <th><i class="fas fa-industry me-2"></i>CO (ppm)</th>
                                <th><i class="fas fa-thermometer-half me-2"></i>Suhu (°C)</th>
                                <th><i class="fas fa-tint me-2"></i>Kelembaban (%)</th>
                                <th><i class="fas fa-sun me-2"></i>Cahaya (lux)</th>
                                <th><i class="fas fa-check-circle me-2"></i>Status</th>
                                <th><i class="fas fa-cog me-2"></i>Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="sensorTableBody">
                            @forelse($sensors as $index => $sensor)
                            @php
                                $status = 'Normal';
                                $class = 'status-normal';
                                $icon = 'fa-check-circle';

                                if ($sensor->nh3 > 50) {
                                    $status = 'Bahaya (NH₃ Tinggi)';
                                    $class = 'status-danger';
                                    $icon = 'fa-exclamation-triangle';
                                } elseif ($sensor->nh3 > 25) {
                                    $status = 'Waspada (NH₃ Sedang)';
                                    $class = 'status-warning';
                                    $icon = 'fa-exclamation-circle';
                                }

                                if ($sensor->co > 50) {
                                    $status = 'Bahaya (CO Tinggi)';
                                    $class = 'status-danger';
                                    $icon = 'fa-exclamation-triangle';
                                } elseif ($sensor->co > 25) {
                                    $status = 'Waspada (CO Sedang)';
                                    $class = 'status-warning';
                                    $icon = 'fa-exclamation-circle';
                                }

                                if ($sensor->temperature >= 34 || $sensor->temperature <= 15) {
                                    $status = 'Bahaya (Suhu Ekstrem)';
                                    $class = 'status-danger';
                                    $icon = 'fa-exclamation-triangle';
                                } elseif ($sensor->temperature >= 31 && $sensor->temperature <= 33) {
                                    $status = 'Waspada (Suhu Tinggi)';
                                    $class = 'status-warning';
                                    $icon = 'fa-exclamation-circle';
                                }
                            @endphp
                            <tr>
                                <td class="fw-bold text-muted">{{ $sensors->firstItem() + $index }}</td>
                                <td data-export="text">{{ $sensor->created_at->format('Y-m-d') }} | {{ $sensor->created_at->format('H:i:s') }}</td>
                                <td><span class="sensor-value text-primary">{{ $sensor->nh3 }}</span></td>
                                <td><span class="sensor-value text-secondary">{{ $sensor->co }}</span></td>
                                <td><span class="sensor-value text-danger">{{ $sensor->temperature }}</span></td>
                                <td><span class="sensor-value text-success">{{ $sensor->humidity }}</span></td>
                                <td><span class="sensor-value text-warning">{{ $sensor->cahaya }}</span></td>
                                <td>
                                    <span class="status-badge {{ $class }}">
                                        <i class="fas {{ $icon }}"></i>
                                        {{ $status }}
                                    </span>
                                </td>
                                <td>
                                    <button class="btn btn-danger btn-sm btn-delete" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#deleteSensorModal"
                                            data-id="{{ $sensor->id }}"
                                            data-date="{{ $sensor->created_at }}">
                                        <i class="fas fa-trash-alt me-1"></i> Hapus
                                    </button>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="9" class="no-data-row">
                                    <div class="no-data-icon">
                                        <i class="fas fa-database"></i>
                                    </div>
                                    <h5 class="text-muted">Belum ada data sensor tersedia</h5>
                                    <p class="text-muted">Data akan muncul setelah sensor mulai mengirim informasi</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="pagination-wrapper d-flex justify-content-center">
                    {{ $sensors->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Tambah Data -->
    <div class="modal fade" id="addSensorModal" tabindex="-1" aria-labelledby="addSensorLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content shadow-lg">
                <div class="modal-header">
                    <h5 class="modal-title" id="addSensorLabel">
                        <i class="fas fa-plus-circle me-2"></i> Tambah Data Sensor
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <form action="{{ url('/insert_sensor') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">
                                <i class="fas fa-smog me-2"></i>NH₃ (ppm)
                            </label>
                            <input type="number" step="0.01" name="nh3" class="form-control" placeholder="Masukkan nilai NH₃" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">
                                <i class="fas fa-industry me-2"></i>CO (ppm)
                            </label>
                            <input type="number" step="0.01" name="co" class="form-control" placeholder="Masukkan nilai CO" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">
                                <i class="fas fa-thermometer-half me-2"></i>Suhu (°C)
                            </label>
                            <input type="number" step="0.1" name="temperature" class="form-control" placeholder="Masukkan nilai suhu" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">
                                <i class="fas fa-tint me-2"></i>Kelembaban (%)
                            </label>
                            <input type="number" step="0.1" name="humidity" class="form-control" placeholder="Masukkan nilai kelembaban" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">
                                <i class="fas fa-sun me-2"></i>Cahaya (lux)
                            </label>
                            <input type="number" step="0.1" name="cahaya" class="form-control" placeholder="Masukkan nilai cahaya" required>
                        </div>
                        <div class="d-flex gap-2 justify-content-end">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                <i class="fas fa-times me-2"></i>Batal
                            </button>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Simpan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Hapus -->
    <div class="modal fade modal-delete" id="deleteSensorModal" tabindex="-1" aria-labelledby="deleteSensorLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content shadow-lg">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteSensorLabel">
                        <i class="fas fa-exclamation-triangle me-2"></i> Konfirmasi Hapus Data
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <div class="delete-icon">
                        <i class="fas fa-trash-alt"></i>
                    </div>
                    <h5 class="mb-3">Apakah Anda yakin ingin menghapus data ini?</h5>
                    <p class="text-muted mb-4">
                        Data sensor dari <strong id="deleteDate"></strong> akan dihapus secara permanen.
                    </p>

                    <form id="deleteForm" method="POST">
                        @csrf
                        @method('DELETE')
                        <div class="d-flex gap-2 justify-content-center">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                <i class="fas fa-times me-2"></i>Batal
                            </button>
                            <button type="submit" class="btn btn-confirm-delete">
                                <i class="fas fa-trash-alt me-2"></i>Ya, Hapus
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="text-white py-4 mt-5">
        <div class="container text-center">
            <p class="mb-2">
                <i class="fas fa-cow"></i> Sistem Pemantauan Ternak
            </p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
async function downloadExcel() {

    // Ambil semua data dari server
    const response = await fetch("/api/sensor-all");
    const data = await response.json();

    if (!data.length) {
        alert("Tidak ada data yang bisa diexport!");
        return;
    }

    // Header kolom
    const header = [
        "ID",
        "Tanggal",
        "Waktu",
        "NH₃ (ppm)",
        "CO (ppm)",
        "Suhu (°C)",
        "Kelembaban (%)",
        "Cahaya (lux)"
    ];

    // Format data sesuai kolom
    const rows = data.map(item => [
        item.id,
        item.created_at.split("T")[0],                     // tanggal
        item.created_at.split("T")[1].split(".")[0],       // waktu
        item.nh3,
        item.co,
        item.temperature,
        item.humidity,
        item.cahaya
    ]);

    // Buat worksheet & workbook
    const worksheet = XLSX.utils.aoa_to_sheet([header, ...rows]);
    const workbook = XLSX.utils.book_new();

    XLSX.utils.book_append_sheet(workbook, worksheet, "Sensor Lingkungan");

    // Export
    XLSX.writeFile(workbook, "data_sensor_lingkungan.xlsx");
}
</script>

    <script>
        // Data dari Laravel
        const labels = @json($sensors->pluck('created_at')->map(fn($d) => $d->format('H:i')));
        const nh3Data = @json($sensors->pluck('nh3'));
        const coData = @json($sensors->pluck('co'));
        const tempData = @json($sensors->pluck('temperature'));
        const humidityData = @json($sensors->pluck('humidity'));
        const cahayaData = @json($sensors->pluck('cahaya'));

        // Initialize Chart
        const ctx = document.getElementById('sensorChart').getContext('2d');
        const sensorChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [
                    {
                        label: 'NH₃ (ppm)',
                        data: nh3Data,
                        borderColor: '#0d6efd',
                        backgroundColor: 'rgba(13, 110, 253, 0.1)',
                        tension: 0.4,
                        borderWidth: 3,
                        pointRadius: 4,
                        pointHoverRadius: 6
                    },
                    {
                        label: 'CO (ppm)',
                        data: coData,
                        borderColor: '#6c757d',
                        backgroundColor: 'rgba(108, 117, 125, 0.1)',
                        tension: 0.4,
                        borderWidth: 3,
                        pointRadius: 4,
                        pointHoverRadius: 6
                    },
                    {
                        label: 'Suhu (°C)',
                        data: tempData,
                        borderColor: '#dc3545',
                        backgroundColor: 'rgba(220, 53, 69, 0.1)',
                        tension: 0.4,
                        borderWidth: 3,
                        pointRadius: 4,
                        pointHoverRadius: 6
                    },
                    {
                        label: 'Kelembaban (%)',
                        data: humidityData,
                        borderColor: '#28a745',
                        backgroundColor: 'rgba(40, 167, 69, 0.1)',
                        tension: 0.4,
                        borderWidth: 3,
                        pointRadius: 4,
                        pointHoverRadius: 6
                    },
                    {
                        label: 'Cahaya (lux)',
                        data: cahayaData,
                        borderColor: '#ffc107',
                        backgroundColor: 'rgba(255, 193, 7, 0.1)',
                        tension: 0.4,
                        borderWidth: 3,
                        pointRadius: 4,
                        pointHoverRadius: 6
                    }
                ]
            },
            options: {
                responsive: true,
                interaction: { intersect: false, mode: 'index' },
                plugins: { legend: { position: 'top' } },
                scales: { y: { beginAtZero: true } }
            }
        });

        // Hapus Data
        document.addEventListener('DOMContentLoaded', function() {
            const deleteButtons = document.querySelectorAll('.btn-delete');
            const deleteForm = document.getElementById('deleteForm');
            const deleteDate = document.getElementById('deleteDate');
            deleteButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const id = this.getAttribute('data-id');
                    const date = this.getAttribute('data-date');
                    deleteDate.textContent = date;
                    deleteForm.action = `/sensor/${id}`;
                });
            });
        });
    </script>

</body>
</html>
