<div class="container-fluid">
    <h2 class="mb-4">Dashboard</h2>

    <!-- Statistik Cards -->
    <div class="row mb-4">
        <?php
        // Total Siswa
        $result = $db->query("SELECT COUNT(*) as total FROM siswa");
        $totalSiswa = $result->fetch_assoc()['total'];

        // Total Guru
        $result = $db->query("SELECT COUNT(*) as total FROM guru");
        $totalGuru = $result->fetch_assoc()['total'];

        // Total Mapel
        $result = $db->query("SELECT COUNT(*) as total FROM mapel");
        $totalMapel = $result->fetch_assoc()['total'];

        // Total Kelas
        $result = $db->query("SELECT COUNT(*) as total FROM kelas");
        $totalKelas = $result->fetch_assoc()['total'];
        ?>

        <div class="col-md-3 mb-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title">Total Siswa</h6>
                            <h2 class="mb-0"><?php echo $totalSiswa; ?></h2>
                        </div>
                        <i class="fas fa-users fa-3x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title">Total Guru</h6>
                            <h2 class="mb-0"><?php echo $totalGuru; ?></h2>
                        </div>
                        <i class="fas fa-chalkboard-teacher fa-3x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title">Mata Pelajaran</h6>
                            <h2 class="mb-0"><?php echo $totalMapel; ?></h2>
                        </div>
                        <i class="fas fa-book fa-3x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title">Total Kelas</h6>
                            <h2 class="mb-0"><?php echo $totalKelas; ?></h2>
                        </div>
                        <i class="fas fa-door-open fa-3x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Grafik dan Jadwal -->
    <div class="row">
        <div class="col-md-8 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5>Grafik Perkembangan Nilai</h5>
                </div>
                <div class="card-body">
                    <canvas id="nilaiChart" style="height: 300px;"></canvas>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5>Jadwal Hari Ini</h5>
                </div>
                <div class="card-body">
                    <?php
                    $hari = date('l');
                    $hariIndo = [
                        'Monday' => 'Senin',
                        'Tuesday' => 'Selasa',
                        'Wednesday' => 'Rabu',
                        'Thursday' => 'Kamis',
                        'Friday' => 'Jumat',
                        'Saturday' => 'Sabtu',
                        'Sunday' => 'Minggu'
                    ];

                    $hariIni = $hariIndo[$hari];

                    $query = "SELECT j.*, m.nama_mapel, k.nama_kelas, g.nama as nama_guru 
                              FROM jadwal j 
                              JOIN mapel m ON j.mapel_id = m.id 
                              JOIN kelas k ON j.kelas_id = k.id 
                              JOIN guru g ON j.guru_id = g.id 
                              WHERE j.hari = '$hariIni'
                              ORDER BY j.jam_mulai";

                    $result = $db->query($query);

                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                    ?>
                            <div class="alert alert-info mb-2">
                                <small><?php echo date('H:i', strtotime($row['jam_mulai'])) . ' - ' . date('H:i', strtotime($row['jam_selesai'])); ?></small>
                                <h6 class="mb-0"><?php echo $row['nama_mapel']; ?></h6>
                                <small>Kelas <?php echo $row['nama_kelas']; ?> - <?php echo $row['nama_guru']; ?></small>
                            </div>
                    <?php
                        }
                    } else {
                        echo "<p class='text-muted'>Tidak ada jadwal hari ini</p>";
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Grafik Nilai
    const ctx = document.getElementById('nilaiChart').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun'],
            datasets: [{
                label: 'Rata-rata Nilai',
                data: [75, 78, 80, 82, 85, 83],
                borderColor: 'rgb(75, 192, 192)',
                tension: 0.1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false
        }
    });
</script>