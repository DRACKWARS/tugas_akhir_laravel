<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Sensor GY-87 – Analisis Perilaku Sapi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <link href="{{ asset('assets/css/styleP.css') }}" rel="stylesheet">
</head>
<body>

<!-- Loading -->
<div class="loading-overlay" id="loadingOverlay">
    <div class="text-center">
        <div class="spinner mb-3"></div>
        <p style="color: var(--accent-orange); font-weight:600;">Memuat data sensor...</p>
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
                    <a class="nav-link text-white" href="/"><i class="fas fa-home"></i> Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white active" href="#"><i class="fas fa-brain"></i> Sensor GY-87</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<!-- Header -->
<div class="page-header">
    <div class="container">
        <h1><i class="fas fa-brain"></i> Analisis Sensor GY-87</h1>
        <nav class="breadcrumb">
            <a href="/" class="text-white text-decoration-none">Home</a>
            <span class="mx-2">/</span>
            <span>Sensor GY-87</span>
        </nav>
    </div>
</div>

<div class="container my-5">

    <!-- Panduan Interpretasi -->
    <div class="info-alert">
        <h6><i class="fas fa-info-circle me-2"></i>Panduan Interpretasi Data Sensor</h6>
        <ul class="mb-0">
            <li><b>Status Diam:</b> Accelerometer &lt; 0.15 dan Gyroscope &lt; 2 → Sapi istirahat atau berbaring.</li>
            <li><b>Status Ringan:</b> 0.15–0.5 accel dan gyro &lt; 15 → Sapi berdiri atau mengunyah.</li>
            <li><b>Status Aktif:</b> accel &gt; 0.5 dan gyro 15–60 → Sapi berjalan atau bergerak.</li>
            <li><b>Status Sangat Aktif:</b> gyro &gt; 60 → Sapi gelisah atau berlari.</li>
        </ul>
    </div>

    <!-- Data Tabel -->
    <div class="main-card mt-4">
        <div class="card-body p-0">
            <div class="card-header-custom d-flex justify-content-between align-items-center flex-wrap gap-2">
                <h4 class="mb-0"><i class="fas fa-database me-2" style="color: var(--accent-orange);"></i>Data Sensor GY-87 Real-Time</h4>
                <div class="d-flex gap-2">
                    <button class="btn btn-add-data" data-bs-toggle="modal" data-bs-target="#addDataModal">
                        <i class="fas fa-plus-circle me-2"></i> Tambah Data
                    </button>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-hover mb-0 data-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Waktu</th>
                            <th>Accel X</th>
                            <th>Accel Y</th>
                            <th>Accel Z</th>
                            <th>Gyro X</th>
                            <th>Gyro Y</th>
                            <th>Gyro Z</th>
                            <th>Mag X</th>
                            <th>Mag Y</th>
                            <th>Mag Z</th>
                            <th>Temp (°C)</th>
                            <th>Tekanan (Pa)</th>
                            <th>Status</th>
                            <th>Perilaku</th>
                            <th>Arah Gerak</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($gy87 as $index => $data)
                            @php
                                $accel = sqrt(pow($data->accel_x,2) + pow($data->accel_y,2) + pow($data->accel_z,2));
                                $gyro  = sqrt(pow($data->gyro_x,2) + pow($data->gyro_y,2) + pow($data->gyro_z,2));
                                $heading = atan2($data->mag_y, $data->mag_x) * (180 / M_PI);
                                if ($heading < 0) $heading += 360;

                                if ($accel < 0.15 && $gyro < 2) {
                                    $status  = "Diam";
                                    $prilaku = "Istirahat / Berbaring";
                                    $badge   = "secondary";
                                } elseif ($accel >= 0.15 && $accel < 0.5 && $gyro < 15) {
                                    $status  = "Ringan";
                                    $prilaku = "Berdiri / Mengunyah";
                                    $badge   = "info";
                                } elseif ($accel >= 0.5 && $gyro >= 15 && $gyro < 60) {
                                    $status  = "Aktif";
                                    $prilaku = "Berjalan / Bergerak";
                                    $badge   = "success";
                                } elseif ($gyro >= 60) {
                                    $status  = "Sangat Aktif";
                                    $prilaku = "Gelisah / Berlari";
                                    $badge   = "danger";
                                } else {
                                    $status  = "Tidak Teridentifikasi";
                                    $prilaku = "Data tidak stabil";
                                    $badge   = "warning";
                                }
                            @endphp
                            <tr>
                                <td>{{ $gy87->firstItem() + $index }}</td>
                                <td>{{ $data->created_at->format('Y-m-d H:i:s') }}</td>
                                <td class="text-primary">{{ number_format($data->accel_x,2) }}</td>
                                <td class="text-primary">{{ number_format($data->accel_y,2) }}</td>
                                <td class="text-primary">{{ number_format($data->accel_z,2) }}</td>
                                <td class="text-info">{{ number_format($data->gyro_x,2) }}</td>
                                <td class="text-info">{{ number_format($data->gyro_y,2) }}</td>
                                <td class="text-info">{{ number_format($data->gyro_z,2) }}</td>
                                <td class="text-success">{{ number_format($data->mag_x,2) }}</td>
                                <td class="text-success">{{ number_format($data->mag_y,2) }}</td>
                                <td class="text-success">{{ number_format($data->mag_z,2) }}</td>
                                <td class="text-danger">{{ number_format($data->temperature,2) }}</td>
                                <td>{{ number_format($data->pressure,2) }}</td>
                                <td><span class="badge bg-{{ $badge }}">{{ $status }}</span></td>
                                <td class="fw-bold">{{ $prilaku }}</td>
                                <td>{{ round($heading,2) }}°</td>
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
                                <td colspan="17" class="text-center text-muted py-5">
                                    <i class="fas fa-database fa-2x mb-3"></i><br>
                                    Belum ada data sensor GY-87 tersimpan
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="pagination-wrapper d-flex justify-content-center mt-3">
                {{ $gy87->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
</div>

<!-- Modal Tambah Data -->
<div class="modal fade" id="addDataModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content shadow-lg">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title"><i class="fas fa-plus-circle me-2"></i>Tambah Data GY-87</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form action="{{ url('/insert_prilaku') }}" method="POST">
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-4"><label>Accel X</label><input type="number" step="0.01" name="accel_x" class="form-control" required></div>
                        <div class="col-md-4"><label>Accel Y</label><input type="number" step="0.01" name="accel_y" class="form-control" required></div>
                        <div class="col-md-4"><label>Accel Z</label><input type="number" step="0.01" name="accel_z" class="form-control" required></div>
                        <div class="col-md-4"><label>Gyro X</label><input type="number" step="0.01" name="gyro_x" class="form-control" required></div>
                        <div class="col-md-4"><label>Gyro Y</label><input type="number" step="0.01" name="gyro_y" class="form-control" required></div>
                        <div class="col-md-4"><label>Gyro Z</label><input type="number" step="0.01" name="gyro_z" class="form-control" required></div>
                        <div class="col-md-4"><label>Mag X</label><input type="number" step="0.01" name="mag_x" class="form-control" required></div>
                        <div class="col-md-4"><label>Mag Y</label><input type="number" step="0.01" name="mag_y" class="form-control" required></div>
                        <div class="col-md-4"><label>Mag Z</label><input type="number" step="0.01" name="mag_z" class="form-control" required></div>
                        <div class="col-md-6"><label>Suhu (°C)</label><input type="number" step="0.1" name="temperature" class="form-control" required></div>
                        <div class="col-md-6"><label>Tekanan (Pa)</label><input type="number" step="0.1" name="pressure" class="form-control" required></div>
                    </div>
                    <div class="d-flex justify-content-end gap-2 mt-4">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-success">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal Delete -->
<div class="modal fade" id="deleteDataModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content shadow-lg">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title"><i class="fas fa-exclamation-triangle me-2"></i>Hapus Data</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center">
                <i class="fas fa-trash-alt fa-3x text-danger mb-3"></i>
                <h5>Yakin ingin menghapus data ini?</h5>
                <p class="text-muted mb-4">Data dari <strong id="deleteDate"></strong> akan dihapus permanen.</p>
                <form id="deleteForm" method="GET">
                    <div class="d-flex justify-content-center gap-2">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-danger">Ya, Hapus</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<footer class="text-white py-4 mt-5">
    <div class="container text-center">
        <p class="mb-0"><i class="fas fa-cow"></i> Sistem Pemantauan Ternak</p>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const deleteButtons = document.querySelectorAll('.btn-delete');
    const deleteForm = document.getElementById('deleteForm');
    const deleteDate = document.getElementById('deleteDate');
    const overlay = document.getElementById('loadingOverlay');

    deleteButtons.forEach(btn=>{
        btn.addEventListener('click',()=>{
            deleteDate.textContent = btn.dataset.date;
            deleteForm.action = `/delete_prilaku/${btn.dataset.id}`;
        });
    });

    window.addEventListener('load',()=>setTimeout(()=>overlay.classList.remove('show'),500));
});
</script>
</body>
</html>
