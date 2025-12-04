<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Indeks Stres Panas (THI) - Smart Cattle Monitor</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link href="{{ asset('assets/css/styleT.css') }}" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
</head>
<body>

    <!-- Loading Overlay -->
    <div class="loading-overlay" id="loadingOverlay">
        <div class="text-center">
            <div class="spinner mb-3"></div>
            <p style="color: var(--danger-red); font-weight: 600;">Memuat data THI...</p>
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
                            <i class="fas fa-fire"></i> Heat Stress (THI)
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Page Header -->
    <div class="page-header">
        <div class="container">
            <div class="fire-icon mb-3">
                <i class="fas fa-fire"></i>
            </div>
            <h1>Detail Indeks Stres Panas (THI)</h1>
            <p>Pemantauan historis nilai THI untuk mendeteksi potensi stres panas pada sapi</p>
        </div>
    </div>

    <div class="container my-5">

        <!-- Chart Section -->
        <div class="chart-container">
            <h4 class="mb-4">
                <i class="fas fa-chart-line me-2" style="color: var(--danger-red);"></i>
                Grafik Trend Heat Stress Index
            </h4>
            <canvas id="thiChart" width="400" height="100"></canvas>
        </div>

        <!-- Guide Card -->
        <div class="guide-card">
            <div class="guide-header">
                <h5 class="guide-title">
                    <i class="fas fa-thermometer-half"></i>
                    Panduan Status Heat Stress (THI)
                </h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table guide-table mb-0">
                        <thead>
                            <tr>
                                <th>Rentang THI</th>
                                <th>Kategori</th>
                                <th>Keterangan</th>
                                <th>Tindakan</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="fw-bold text-success">&lt; 72</td>
                                <td>
                                    <span class="badge bg-success">
                                        <i class="fas fa-check-circle"></i> Nyaman
                                    </span>
                                </td>
                                <td>Sapi dalam kondisi normal tanpa stres panas.</td>
                                <td class="text-muted">Tidak perlu tindakan khusus</td>
                            </tr>
                            <tr>
                                <td class="fw-bold text-warning">72 – 78</td>
                                <td>
                                    <span class="badge bg-warning text-dark">
                                        <i class="fas fa-exclamation-circle"></i> Stres Ringan
                                    </span>
                                </td>
                                <td>Suhu mulai mengganggu kenyamanan sapi, aktivitas masih normal.</td>
                                <td>Tingkatkan ventilasi kandang</td>
                            </tr>
                            <tr>
                                <td class="fw-bold" style="color: #fd7e14;">79 – 88</td>
                                <td>
                                    <span class="badge bg-primary">
                                        <i class="fas fa-exclamation-triangle"></i> Stres Sedang
                                    </span>
                                </td>
                                <td>Sapi mulai menunjukkan stres panas, perlu perbaikan ventilasi.</td>
                                <td>Pastikan air minum cukup dan sejuk</td>
                            </tr>
                            <tr>
                                <td class="fw-bold text-danger">&gt; 88</td>
                                <td>
                                    <span class="badge bg-danger">
                                        <i class="fas fa-skull-crossbones"></i> Stres Berat
                                    </span>
                                </td>
                                <td>Berbahaya — sapi dapat mengalami heat stress berat.</td>
                                <td class="text-danger fw-bold">Lakukan pendinginan segera!</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Main Data Card -->
        <div class="main-card">
            <div class="card-body p-0">
                
                <div class="card-header-custom d-flex justify-content-between align-items-center">
                    <h4 class="mb-0 text-danger">
                        <i class="fas fa-table me-2"></i>
                        Tabel Data Historis Indeks THI
                    </h4>
                    <div class="d-flex gap-2">
                        <button class="btn btn-add-data" onclick="downloadExcel()">
                            <i class="fas fa-file-excel"></i> Export Excel
                        </button>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover mb-0 data-table" id="thiTable">
                        <thead>
                            <tr>
                                <th><i class="fas fa-hashtag me-2"></i>No</th>
                                <th><i class="fas fa-clock me-2"></i>Tanggal & Waktu</th>
                                <th><i class="fas fa-thermometer-half me-2"></i>Suhu (°C)</th>
                                <th><i class="fas fa-tint me-2"></i>Kelembaban (%)</th>
                                <th><i class="fas fa-fire me-2"></i>THI</th>
                                <th><i class="fas fa-check-circle me-2"></i>Kategori</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td colspan="6" class="no-data-row">
                                    <div class="spinner-border text-danger" role="status">
                                        <span class="visually-hidden">Loading...</span>
                                    </div>
                                    <p class="mt-3 text-muted">Memuat data...</p>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

            </div>
        </div>

        <div class="text-center mt-4">
            <a href="/" class="btn btn-back">
                <i class="fas fa-arrow-left me-2"></i>
                Kembali ke Beranda
            </a>
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
            function downloadExcel() {
                let rows = [];
                let table = document.querySelectorAll("#thiTable tr");

                table.forEach((tr, i) => {
                    let row = [];
                    let cells = tr.querySelectorAll("th, td");

                    cells.forEach(td => {
                        if (td.getAttribute("data-export") === "datetime") {
                            row.push({ v: td.innerText, t: "s" }); // paksa Excel baca sebagai string
                        } else {
                            row.push(td.innerText);
                        }
                    });

                    rows.push(row);
                });

                let sheet = XLSX.utils.aoa_to_sheet(rows);

                // Format semua kolom datetime sebagai TEXT ("@")
                Object.keys(sheet).forEach(cell => {
                    if (sheet[cell].v && typeof sheet[cell].v === "string" && sheet[cell].v.includes(":")) {
                        sheet[cell].t = "s";
                        sheet[cell].z = "@";
                    }
                });

                let workbook = XLSX.utils.book_new();
                XLSX.utils.book_append_sheet(workbook, sheet, "Data THI");

                XLSX.writeFile(workbook, "data_sensor_thi.xlsx");
            }

            </script>
    
    <script>
        function hitungTHI(temp, hum) {
            return (1.8 * temp + 32) - (0.55 - 0.0055 * hum) * (1.8 * temp - 26);
        }

        function kategoriTHI(thi) {
            if (thi < 72) return "Nyaman";
            if (thi < 78) return "Stres Ringan";
            if (thi < 89) return "Stres Sedang";
            return "Stres Berat";
        }

        function getBadgeClass(kategori) {
            if (kategori === "Nyaman") return "bg-success";
            if (kategori === "Stres Ringan") return "bg-warning text-dark";
            if (kategori === "Stres Sedang") return "bg-primary";
            return "bg-danger";
        }

        function getBadgeIcon(kategori) {
            if (kategori === "Nyaman") return "fa-check-circle";
            if (kategori === "Stres Ringan") return "fa-exclamation-circle";
            if (kategori === "Stres Sedang") return "fa-exclamation-triangle";
            return "fa-skull-crossbones";
        }

        // Sample data for chart
        let chartLabels = [];
        let chartData = [];

        $(document).ready(function() {
            // Show loading
            $('#loadingOverlay').addClass('show');

            $.getJSON("/sensor/all", function(data) {
                let tbody = $("#thiTable tbody");
                tbody.empty();

                if (!data || data.length === 0) {
                    tbody.append(`
                        <tr>
                            <td colspan="6" class="no-data-row">
                                <div class="no-data-icon">
                                    <i class="fas fa-database"></i>
                                </div>
                                <h5 class="text-muted">Tidak ada data tersedia</h5>
                                <p class="text-muted">Data akan muncul setelah sensor mulai mengirim informasi</p>
                            </td>
                        </tr>
                    `);
                    $('#loadingOverlay').removeClass('show');
                    return;
                }

                // Process data for table and chart
                chartLabels = [];
                chartData = [];

                data.forEach((item, index) => {
                    let temp = parseFloat(item.temperature);
                    let hum = parseFloat(item.humidity);
                    let thi = hitungTHI(temp, hum);
                    let kategori = kategoriTHI(thi);
                    let badgeClass = getBadgeClass(kategori);
                    let badgeIcon = getBadgeIcon(kategori);

                    // Add to chart data
                    if (index < 20) { // Last 20 records for chart
                        chartLabels.push(item.created_at.split(' ')[1].substring(0, 5));
                        chartData.push(thi.toFixed(1));
                    }

                    let row = `
                        <tr>
                            <td class="fw-bold text-muted">${index + 1}</td>
                            <td class="text-muted" data-export="datetime">${item.created_at}</td>
                            <td><span class="sensor-value text-danger">${temp.toFixed(1)}</span></td>
                            <td><span class="sensor-value text-success">${hum.toFixed(1)}</span></td>
                            <td><span class="sensor-value fw-bold" style="color: #dc3545;">${thi.toFixed(1)}</span></td>
                            <td>
                                <span class="badge ${badgeClass}">
                                    <i class="fas ${badgeIcon}"></i>
                                    ${kategori}
                                </span>
                            </td>
                        </tr>
                    `;
                    tbody.append(row);
                });

                // Initialize chart
                initChart();

                // Hide loading
                $('#loadingOverlay').removeClass('show');

            }).fail(function() {
                $("#thiTable tbody").html(`
                    <tr>
                        <td colspan="6" class="no-data-row">
                            <div class="no-data-icon text-danger">
                                <i class="fas fa-exclamation-triangle"></i>
                            </div>
                            <h5 class="text-danger">Gagal memuat data</h5>
                            <p class="text-muted">Silakan refresh halaman atau hubungi administrator</p>
                        </td>
                    </tr>
                `);
                $('#loadingOverlay').removeClass('show');
            });
        });

        function initChart() {
            const ctx = document.getElementById('thiChart').getContext('2d');
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: chartLabels.reverse(),
                    datasets: [{
                        label: 'THI Value',
                        data: chartData.reverse(),
                        borderColor: '#dc3545',
                        backgroundColor: 'rgba(220, 53, 69, 0.1)',
                        tension: 0.4,
                        borderWidth: 3,
                        pointRadius: 5,
                        pointHoverRadius: 7,
                        pointBackgroundColor: '#dc3545',
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    interaction: {
                        intersect: false,
                        mode: 'index'
                    },
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            backgroundColor: 'rgba(0, 0, 0, 0.8)',
                            padding: 12,
                            titleFont: {
                                size: 14,
                                weight: 'bold'
                            },
                            bodyFont: {
                                size: 13
                            },
                            cornerRadius: 8,
                            callbacks: {
                                label: function(context) {
                                    let value = context.parsed.y;
                                    let kategori = kategoriTHI(value);
                                    return `THI: ${value} (${kategori})`;
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: false,
                            min: 60,
                            max: 100,
                            grid: {
                                color: 'rgba(0, 0, 0, 0.05)'
                            },
                            ticks: {
                                font: {
                                    size: 11,
                                    weight: '600'
                                }
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            },
                            ticks: {
                                font: {
                                    size: 11,
                                    weight: '600'
                                }
                            }
                        }
                    }
                }
            });
        }
    </script>

</body>
</html>