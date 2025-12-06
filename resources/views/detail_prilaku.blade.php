<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Sensor Perilaku Sapi - Smart Cattle Monitor</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link href="{{ asset('assets/css/styleP.css') }}" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
</head>
<body>

    <!-- Loading Overlay -->
    <div class="loading-overlay" id="loadingOverlay">
        <div class="text-center">
            <div class="spinner mb-3"></div>
            <p style="color: var(--accent-orange); font-weight: 600;">Memuat data sensor...</p>
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
                            <i class="fas fa-brain"></i> Sensor Perilaku
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
                <i class="fas fa-brain"></i>
                Analisis Sensor Perilaku Sapi
            </h1>
            <nav class="breadcrumb">
                <a href="/" class="text-white text-decoration-none">Home</a>
                <span class="mx-2">/</span>
                <span>Sensor Prilaku</span>
            </nav>
        </div>
    </div>

    <div class="container my-5">
        <!-- Information Alert -->
        <div class="info-alert">
            <h6>
                <i class="fas fa-info-circle me-2"></i>
                Panduan Interpretasi Data Sensor
            </h6>
            <ul class="mb-0">
                <li><b>Status Diam:</b> Accelerometer &lt; 0.2 dan Gyroscope &lt; 1 — Sapi sedang istirahat atau tidur.</li>
                <li><b>Status Aktif (Bergerak Ringan):</b> Aktivitas sedang — Sapi bergerak perlahan atau mengubah posisi.</li>
                <li><b>Status Aktif (Berjalan/Makan):</b> Accelerometer &gt; 0.5 dan Gyroscope &gt; 20 — Sapi sedang berjalan atau makan.</li>
                <li><b>Suhu Perangkat:</b> Monitoring suhu sensor untuk memastikan perangkat bekerja optimal.</li>
            </ul>
        </div>

        <!-- Main Data Card -->
        <div class="main-card">
            <div class="card-body p-0">
                
                <div class="card-header-custom d-flex justify-content-between align-items-center flex-wrap gap-2">
                    <h4 class="mb-0">
                        <i class="fas fa-database me-2" style="color: var(--accent-orange);"></i>
                        Data Sensor Real-Time
                    </h4>
                    <div class="d-flex gap-2">
                        <button class="btn btn-add-data" data-bs-toggle="modal" data-bs-target="#addDataModal">
                            <i class="fas fa-plus-circle me-2"></i> Tambah Data
                        </button>
                        <button class="btn btn-success" onclick="downloadExcel()">
                            <i class="fas fa-file-excel"></i> Export Excel
                        </button>
                    </div>
                </div>

                <div class="table-responsive">
                    <table id="tableMPU6050" class="table table-hover mb-0 data-table">
                        <thead>
                            <tr>
                                <th><i class="fas fa-hashtag me-1"></i>No</th>
                                <th><i class="fas fa-clock me-1"></i>Waktu</th>
                                <th><i class="fas fa-arrows-alt me-1"></i>Accel X</th>
                                <th><i class="fas fa-arrows-alt me-1"></i>Accel Y</th>
                                <th><i class="fas fa-arrows-alt me-1"></i>Accel Z</th>
                                <th><i class="fas fa-sync me-1"></i>Gyro X</th>
                                <th><i class="fas fa-sync me-1"></i>Gyro Y</th>
                                <th><i class="fas fa-sync me-1"></i>Gyro Z</th>
                                <th><i class="fas fa-thermometer-half me-1"></i>Suhu (°C)</th>
                                <th><i class="fas fa-wave-square me-1"></i>Status</th>
                                <th><i class="fas fa-brain me-1"></i>Perilaku</th>
                                <th><i class="fas fa-cog me-1"></i>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($mpuData as $index => $data)
                                @php
                                    $accel = abs($data->accel_x) + abs($data->accel_y) + abs($data->accel_z);
                                    $gyro  = abs($data->gyro_x) + abs($data->gyro_y) + abs($data->gyro_z);

                                    if ($accel < 0.2 && $gyro < 1) {
                                        $status  = "Diam";
                                        $prilaku = "Istirahat";
                                        $badge   = "secondary";
                                    } elseif ($accel > 0.5 && $gyro > 20) {
                                        $status  = "Aktif";
                                        $prilaku = "Berjalan / Makan";
                                        $badge   = "success";
                                    } else {
                                        $status  = "Aktif";
                                        $prilaku = "Bergerak Ringan";
                                        $badge   = "info";
                                    }
                                @endphp

                                <tr>
                                    <td class="fw-bold text-muted">{{ $mpuData->firstItem() + $index }}</td>
                                    <td data-export="text">{{ $data->created_at->format('Y-m-d') }} | {{ $data->created_at->format('H:i:s') }}</td>
                                    <td class="sensor-value text-primary">{{ number_format($data->accel_x, 2) }}</td>
                                    <td class="sensor-value text-primary">{{ number_format($data->accel_y, 2) }}</td>
                                    <td class="sensor-value text-primary">{{ number_format($data->accel_z, 2) }}</td>
                                    <td class="sensor-value text-info">{{ number_format($data->gyro_x, 2) }}</td>
                                    <td class="sensor-value text-info">{{ number_format($data->gyro_y, 2) }}</td>
                                    <td class="sensor-value text-info">{{ number_format($data->gyro_z, 2) }}</td>
                                    <td class="sensor-value text-danger">{{ number_format($data->temperature, 2) }}</td>
                                    <td>
                                        <span class="badge bg-{{ $badge }} px-3 py-2">
                                            <i class="fas fa-wave-square me-1"></i>{{ $status }}
                                        </span>
                                    </td>
                                    <td class="fw-bold">{{ $prilaku }}</td>
                                    <td>
                                        <button class="btn btn-delete"
                                                data-bs-toggle="modal"
                                                data-bs-target="#deleteDataModal"
                                                data-id="{{ $data->id }}"
                                                data-date="{{ $data->created_at }}">
                                            <i class="fas fa-trash-alt me-1"></i> Hapus
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="12" class="no-data-row">
                                        <div class="no-data-icon">
                                            <i class="fas fa-database"></i>
                                        </div>
                                        <h5 class="text-muted">Belum ada data perilaku sapi tersedia</h5>
                                        <p class="text-muted">Data akan muncul setelah sensor mulai mengirim informasi</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="pagination-wrapper d-flex justify-content-center">
                    {{ $mpuData->links('pagination::bootstrap-5') }}
                </div>

            </div>
        </div>
    </div>

    <!-- Modal Tambah Data -->
    <div class="modal fade" id="addDataModal" tabindex="-1" aria-labelledby="addDataModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content shadow-lg">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title" id="addDataModalLabel">
                        <i class="fas fa-plus-circle me-2"></i> Tambah Data Perilaku Sapi
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ url('/insert_mpu') }}" method="POST">
                        @csrf
                        
                        <h6 class="text-success mb-3">
                            <i class="fas fa-arrows-alt me-2"></i>Data Accelerometer
                        </h6>
                        <div class="row g-3 mb-3">
                            <div class="col-md-4">
                                <label class="form-label">
                                    <i class="fas fa-arrows-alt-h me-1"></i>Accel X
                                </label>
                                <input type="number" step="0.01" name="accel_x" class="form-control" placeholder="0.00" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">
                                    <i class="fas fa-arrows-alt-v me-1"></i>Accel Y
                                </label>
                                <input type="number" step="0.01" name="accel_y" class="form-control" placeholder="0.00" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">
                                    <i class="fas fa-compress-arrows-alt me-1"></i>Accel Z
                                </label>
                                <input type="number" step="0.01" name="accel_z" class="form-control" placeholder="0.00" required>
                            </div>
                        </div>

                        <h6 class="text-info mb-3">
                            <i class="fas fa-sync me-2"></i>Data Gyroscope
                        </h6>
                        <div class="row g-3 mb-3">
                            <div class="col-md-4">
                                <label class="form-label">
                                    <i class="fas fa-redo me-1"></i>Gyro X
                                </label>
                                <input type="number" step="0.01" name="gyro_x" class="form-control" placeholder="0.00" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">
                                    <i class="fas fa-redo me-1"></i>Gyro Y
                                </label>
                                <input type="number" step="0.01" name="gyro_y" class="form-control" placeholder="0.00" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">
                                    <i class="fas fa-redo me-1"></i>Gyro Z
                                </label>
                                <input type="number" step="0.01" name="gyro_z" class="form-control" placeholder="0.00" required>
                            </div>
                        </div>

                        <h6 class="text-danger mb-3">
                            <i class="fas fa-thermometer-half me-2"></i>Data Suhu
                        </h6>
                        <div class="mb-3">
                            <label class="form-label">
                                <i class="fas fa-temperature-high me-1"></i>Suhu Perangkat (°C)
                            </label>
                            <input type="number" step="0.1" name="temperature" class="form-control" placeholder="25.0" required>
                        </div>

                        <div class="d-flex justify-content-end gap-2 mt-4">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                <i class="fas fa-times me-2"></i> Batal
                            </button>
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-save me-2"></i> Simpan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Hapus Data -->
    <div class="modal fade" id="deleteDataModal" tabindex="-1" aria-labelledby="deleteDataModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content shadow-lg">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title" id="deleteDataModalLabel">
                        <i class="fas fa-exclamation-triangle me-2"></i> Konfirmasi Hapus Data
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-center">
                    <div class="delete-icon">
                        <i class="fas fa-trash-alt"></i>
                    </div>
                    <h5 class="mb-3">Apakah Anda yakin ingin menghapus data ini?</h5>
                    <p class="text-muted mb-4">Data dari <strong id="deleteDate"></strong> akan dihapus secara permanen.</p>

                    <form id="deleteForm" method="GET">
                    <div class="d-flex justify-content-center gap-2">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="fas fa-times me-2"></i> Batal
                        </button>
                        <button type="submit" class="btn btn-danger">
                            <i class="fas fa-trash-alt me-2"></i> Ya, Hapus
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

    const response = await fetch("/mpu/export-json");
    const data = await response.json();

    if (!data.length) {
        alert("Tidak ada data untuk di-export!");
        return;
    }

    const exportData = data.map((row, i) => {

        // Hitung status dan perilaku (logika persis dari Blade)
        const accelTotal = Math.abs(row.accel_x) + Math.abs(row.accel_y) + Math.abs(row.accel_z);
        const gyroTotal  = Math.abs(row.gyro_x) + Math.abs(row.gyro_y) + Math.abs(row.gyro_z);

        let status = "";
        let prilaku = "";

        if (accelTotal < 0.2 && gyroTotal < 1) {
            status  = "Diam";
            prilaku = "Istirahat";
        } else if (accelTotal > 0.5 && gyroTotal > 20) {
            status  = "Aktif";
            prilaku = "Berjalan / Makan";
        } else {
            status  = "Aktif";
            prilaku = "Bergerak Ringan";
        }

        return {
            No: i + 1,
            Waktu: new Date(row.created_at).toLocaleString("id-ID", {
                year: "numeric",
                month: "2-digit",
                day: "2-digit",
                hour: "2-digit",
                minute: "2-digit",
                second: "2-digit"
            }),
            Accel_X: row.accel_x,
            Accel_Y: row.accel_y,
            Accel_Z: row.accel_z,
            Gyro_X: row.gyro_x,
            Gyro_Y: row.gyro_y,
            Gyro_Z: row.gyro_z,
            Suhu: row.temperature,
            Status: status,
            Perilaku: prilaku
        };
    });

    const worksheet = XLSX.utils.json_to_sheet(exportData);
    const workbook  = XLSX.utils.book_new();
    XLSX.utils.book_append_sheet(workbook, worksheet, "Data MPU6050");

    XLSX.writeFile(workbook, "data_sensor_mpu6050.xlsx");
}

    </script>
    <script>

        // Handle delete button click
            document.addEventListener('DOMContentLoaded', function () {
                const deleteButtons = document.querySelectorAll('.btn-delete');
                const deleteForm = document.getElementById('deleteForm');
                const deleteDate = document.getElementById('deleteDate');

                deleteButtons.forEach(button => {
                    button.addEventListener('click', function () {
                        const id = this.getAttribute('data-id');
                        const date = this.getAttribute('data-date');

                        deleteDate.textContent = date;

                        deleteForm.action = "/delete_mpu/" + id;
                    });
                });
            });

            // Loading overlay functions
            const loadingOverlay = document.getElementById('loadingOverlay');
            
            function showLoading() {
                loadingOverlay.classList.add('show');
            }

            function hideLoading() {
                loadingOverlay.classList.remove('show');
            }

            // Show loading on page load
            window.addEventListener('load', function() {
                setTimeout(hideLoading, 500);
            });

            // Show loading on form submit
            document.querySelectorAll('form').forEach(form => {
                form.addEventListener('submit', function() {
                    showLoading();
                });
            });

        // Add smooth scrolling
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth'
                    });
                }
            });
        });
    </script>

</body>
</html>