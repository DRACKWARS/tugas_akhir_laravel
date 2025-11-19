<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Monitoring Sapi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link href="{{ asset('assets/css/style.css') }}" rel="stylesheet">
</head>
<body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark shadow-sm">
        <div class="container">
            <a class="navbar-brand fw-bold" href="#">
                <i class="fas fa-cow"></i> Smart Cattle Monitor
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link text-white" href="#">
                            <i class="fas fa-home"></i> Home
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <div class="hero-section">
        <div class="hero-content text-white">
            <h1 class="hero-title">
                <i class="fas fa-chart-line"></i>
                Smart Cattle Monitoring
            </h1>
            <p class="hero-subtitle">
                <span class="status-indicator"></span>
                Monitoring real-time kondisi lingkungan & perilaku sapi secara cerdas
            </p>
        </div>
    </div>

    <div class="container my-5">

        <!-- Environmental Sensors Section -->
        <div class="section-card environment-section p-4 mb-5">
            <div class="row justify-content-center text-center">
                <div class="col-12 mb-4">
                    <h2 class="section-title text-success">
                        <i class="fas fa-seedling"></i>
                        Sensor Lingkungan Kandang
                    </h2>
                    <p class="section-description">
                        Pemantauan otomatis terhadap kondisi lingkungan kandang untuk menjaga kesehatan dan kenyamanan sapi dengan teknologi IoT terdepan.
                    </p>
                </div>

                <!-- Sensor Cards -->
                <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
                    <div class="sensor-card nh3-card">
                        <div class="card-body text-center p-3">
                            <div class="sensor-value text-primary" id="nh3">--</div>
                            <div class="sensor-unit">ppm</div>
                            <div class="sensor-label">
                                <i class="fas fa-smog"></i>
                                <span>Amonia (NH₃)</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
                    <div class="sensor-card co-card">
                        <div class="card-body text-center p-3">
                            <div class="sensor-value text-secondary" id="co">--</div>
                            <div class="sensor-unit">ppm</div>
                            <div class="sensor-label">
                                <i class="fas fa-industry"></i>
                                <span>Karbon Monoksida (CO)</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
                    <div class="sensor-card temp-card">
                        <div class="card-body text-center p-3">
                            <div class="sensor-value text-danger" id="temp">--</div>
                            <div class="sensor-unit">°C</div>
                            <div class="sensor-label">
                                <i class="fas fa-thermometer-half"></i>
                                <span>Suhu Udara</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
                    <div class="sensor-card humidity-card">
                        <div class="card-body text-center p-3">
                            <div class="sensor-value text-success" id="humidity">--</div>
                            <div class="sensor-unit">%</div>
                            <div class="sensor-label">
                                <i class="fas fa-tint"></i>
                                <span>Kelembaban</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
                    <div class="sensor-card light-card">
                        <div class="card-body text-center p-3">
                            <div class="sensor-value text-warning" id="cahaya">--</div>
                            <div class="sensor-unit">lux</div>
                            <div class="sensor-label">
                                <i class="fas fa-sun"></i>
                                <span>Intensitas Cahaya</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-12 text-center mt-4">
                    <a href="/detail_lingkungan" class="btn btn-success detail-btn">
                        <i class="fas fa-chart-area"></i> Lihat Detail Analisis
                    </a>
                </div>
            </div>
        </div>

        <!-- Heat Stress (THI) Section -->
        <div class="section-card thi-section p-4 mb-5">
            <div class="row">
                <div class="col-12 mb-4 text-center">
                    <h2 class="thi-title">
                        <i class="fas fa-fire"></i>
                        Indeks Stres Panas (Heat Stress - THI)
                    </h2>
                    <p class="section-description">
                        Analisis tingkat stres panas sapi berdasarkan suhu dan kelembaban kandang. Semakin tinggi nilai THI, semakin besar potensi sapi mengalami heat stress.
                    </p>
                </div>

                <div class="col-lg-6 col-md-6 mb-3">
                    <div class="sensor-card thi-value-card">
                        <div class="card-body text-center p-4">
                            <div class="sensor-value text-danger" id="thiValue">--</div>
                            <div class="sensor-unit">THI</div>
                            <div class="sensor-label">
                                <i class="fas fa-temperature-high"></i> Nilai Indeks THI
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6 col-md-6 mb-3">
                    <div class="sensor-card thi-status-card">
                        <div class="card-body text-center p-4">
                            <div class="sensor-value" style="color: #fd7e14;" id="thiStatus">--</div>
                            <div class="sensor-label">
                                <i class="fas fa-thermometer-half"></i> Kategori Stres Panas
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-12 text-center mt-4">
                    <a href="/detail_thi" class="btn detail-btn danger">
                        <i class="fas fa-chart-line"></i> Lihat Detail Analisis THI
                    </a>
                </div>
            </div>
        </div>

                <!-- Behavior Sensors Section -->
        <div class="section-card behavior-section p-4 mb-5">
            <div class="row">
                <div class="col-12 mb-4 text-center">
                    <h2 class="section-title" style="color: var(--accent-orange);">
                        <i class="fas fa-cow"></i> Sensor Perilaku Sapi
                    </h2>
                    <p class="section-description">
                        Analisis perilaku sapi menggunakan data dari sensor berbeda untuk mendeteksi aktivitas seperti berdiri, berjalan, makan, atau istirahat secara real-time.
                    </p>
                </div>

                <!-- 🐄 SAPI 1 -->
                <div class="col-lg-6 col-md-6 mb-4">
                    <div class="sensor-card shadow p-4 rounded-4 bg-white">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="fw-bold text-primary"><i class="fas fa-cow"></i> Sapi 1 (Sensor GY-87)</h5>
                        </div>

                        <div class="text-center mb-3">
                            <div class="sensor-value text-info fs-3" id="statusAktivitas1">--</div>
                            <div class="sensor-label"><i class="fas fa-running"></i> Status Aktivitas</div>
                        </div>
                        <div class="text-center">
                            <div class="sensor-value text-purple fs-3" id="prilaku1">--</div>
                            <div class="sensor-label"><i class="fas fa-brain"></i> Perilaku Saat Ini</div>
                        </div>
                        <div class="col-12 text-center mt-4">
                            <a href="/detail_prilaku2" class="btn detail-btn primary">
                                <i class="fas fa-chart-area"></i> Lihat Detail Analisis
                            </a>
                        </div>
                    </div>
                </div>

                <!-- 🐄 SAPI 2 -->
                <div class="col-lg-6 col-md-6 mb-4">
                    <div class="sensor-card shadow p-4 rounded-4 bg-white">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="fw-bold text-success"><i class="fas fa-cow"></i> Sapi 2 (Sensor MPU6050)</h5>
                        </div>

                        <div class="text-center mb-3">
                            <div class="sensor-value text-info fs-3" id="statusAktivitas2">--</div>
                            <div class="sensor-label"><i class="fas fa-running"></i> Status Aktivitas</div>
                        </div>
                        <div class="text-center">
                            <div class="sensor-value text-purple fs-3" id="prilaku2">--</div>
                            <div class="sensor-label"><i class="fas fa-brain"></i> Perilaku Saat Ini</div>
                        </div>
                        <div class="col-12 text-center mt-4">
                            <a href="/detail_prilaku" class="btn detail-btn hover">
                                <i class="fas fa-chart-area"></i> Lihat Detail Analisis
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="text-white py-4 mt-5">
        <div class="container text-center">
            <p class="mb-2">
                <i class="fas fa-cow"></i> Smart Cattle Monitoring System
            </p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
// ==========================
//  🔥 SHOW LOADING EFFECT
// ==========================
function showLoading(elementId) {
    document.getElementById(elementId).innerHTML = 
        '<div class="loading-skeleton" style="height:20px;width:60px;margin:0 auto;"></div>';
}

// ==========================
//  🔥 LOAD SENSOR LINGKUNGAN
// ==========================
function loadSensorData() {
    showLoading('nh3');
    showLoading('co');
    showLoading('temp');
    showLoading('humidity');
    showLoading('cahaya');

    $.getJSON("/sensor/latest", function(data) {
        $("#nh3").text(parseFloat(data.nh3).toFixed(2));
        $("#co").text(parseFloat(data.co).toFixed(2));
        $("#temp").text(parseFloat(data.temperature).toFixed(1));
        $("#humidity").text(parseFloat(data.humidity).toFixed(1));
        $("#cahaya").text(parseFloat(data.cahaya).toFixed(1));
    }).fail(() => {
        $("#nh3, #co, #temp, #humidity, #cahaya").text("--");
    });
}

// ==========================
// 🔥 THI CALCULATOR
// ==========================
function loadTHI() {
    showLoading('thiValue');
    showLoading('thiStatus');

    $.getJSON("/sensor/latest", function(data) {
        let t = parseFloat(data.temperature);
        let h = parseFloat(data.humidity);

        if (isNaN(t) || isNaN(h)) {
            $("#thiValue").text("--");
            $("#thiStatus").text("--");
            return;
        }

        let thi = (1.8 * t + 32) - (0.55 - 0.0055 * h) * (1.8 * t - 26);
        thi = thi.toFixed(1);

        $("#thiValue").text(thi);

        let status = "";
        let color = "";

        if (thi < 72) { status = "Nyaman"; color = "#28a745"; }
        else if (thi < 78) { status = "Stres Ringan"; color = "#ffc107"; }
        else if (thi < 89) { status = "Stres Sedang"; color = "#fd7e14"; }
        else { status = "Stres Berat"; color = "#dc3545"; }

        $("#thiStatus").text(status).css("color", color);

    }).fail(() => {
        $("#thiValue").text("--");
        $("#thiStatus").text("--");
    });
}

// ==========================
// 🔥 PERILAKU SAPI 1 (GY87)
// ==========================
function loadSapi1() {
    fetch("/gy87/prilaku")
        .then(r => r.json())
        .then(d => {
            $("#statusAktivitas1").text(d.status_aktivitas || "--");
            $("#prilaku1").text(d.prilaku || "--");
        })
        .catch(() => {
            $("#statusAktivitas1, #prilaku1").text("--");
        });
}

// ==========================
// 🔥 PERILAKU SAPI 2 (MPU6050)
// ==========================
function loadSapi2() {
    fetch("/sensor/prilaku")
        .then(r => r.json())
        .then(d => {
            $("#statusAktivitas2").text(d.status_aktivitas || "--");
            $("#prilaku2").text(d.prilaku || "--");
        })
        .catch(() => {
            $("#statusAktivitas2, #prilaku2").text("--");
        });
}

// ==========================
// 🔥 AUTO REFRESH SEMUA 5s
// ==========================
function refreshAll() {
    loadSensorData();
    loadTHI();
    loadSapi1();
    loadSapi2();
}

refreshAll();
setInterval(refreshAll, 5000);

// ==========================
// 🔥 SMOOTH SCROLL
// ==========================
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        e.preventDefault();
        const target = document.querySelector(this.getAttribute('href'));
        if (target) {
            target.scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });
        }
    });
});
</script>

<style>
.sensor-card {
    transition: transform 0.2s ease-in-out;
}
.sensor-card:hover {
    transform: scale(1.02);
}
.text-purple {
    color: #6f42c1;
}
</style>


</body>
</html>