<div class="container-fluid px-0">
    <!-- Welcome Banner -->
    <div class="alert alert-primary d-flex align-items-center mb-4">
        <i class="bi bi-info-circle-fill me-2 fs-4"></i>
        <div>
            <strong>Selamat datang, <?php echo $_SESSION['username']; ?>!</strong> di Sistem Akademik MTs Negeri Ngada
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row g-3 mb-4">
        <?php
        // Hitung total
        $totalSiswa = $db->query("SELECT COUNT(*) as total FROM siswa")->fetch_assoc()['total'];
        $totalGuru = $db->query("SELECT COUNT(*) as total FROM guru")->fetch_assoc()['total'];
        $totalMapel = $db->query("SELECT COUNT(*) as total FROM mapel")->fetch_assoc()['total'];
        $totalKelas = $db->query("SELECT COUNT(*) as total FROM kelas")->fetch_assoc()['total'];
        ?>

        <div class="col-6 col-md-3">
            <div class="stat-card">
                <div class="stat-icon bg-primary bg-opacity-10 text-primary">
                    <i class="bi bi-people-fill"></i>
                </div>
                <div>
                    <small class="text-muted">Total Siswa</small>
                    <h3 class="mb-0"><?php echo $totalSiswa; ?></h3>
                </div>
            </div>
        </div>

        <div class="col-6 col-md-3">
            <div class="stat-card">
                <div class="stat-icon bg-success bg-opacity-10 text-success">
                    <i class="bi bi-person-badge"></i>
                </div>
                <div>
                    <small class="text-muted">Total Guru</small>
                    <h3 class="mb-0"><?php echo $totalGuru; ?></h3>
                </div>
            </div>
        </div>

        <div class="col-6 col-md-3">
            <div class="stat-card">
                <div class="stat-icon bg-warning bg-opacity-10 text-warning">
                    <i class="bi bi-book-fill"></i>
                </div>
                <div>
                    <small class="text-muted">Mata Pelajaran</small>
                    <h3 class="mb-0"><?php echo $totalMapel; ?></h3>
                </div>
            </div>
        </div>

        <div class="col-6 col-md-3">
            <div class="stat-card">
                <div class="stat-icon bg-info bg-opacity-10 text-info">
                    <i class="bi bi-door-open-fill"></i>
                </div>
                <div>
                    <small class="text-muted">Total Kelas</small>
                    <h3 class="mb-0"><?php echo $totalKelas; ?></h3>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="row g-3 mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-white">
                    <h5 class="mb-0"><i class="bi bi-lightning-charge-fill text-warning me-2"></i>Aksi Cepat</h5>
                </div>
                <div class="card-body">
                    <div class="row g-2">
                        <div class="col-6 col-md-3">
                            <a href="index.php?page=siswa&action=tambah" class="btn btn-outline-primary w-100">
                                <i class="bi bi-person-plus"></i> <span class="d-none d-md-inline">Tambah Siswa</span>
                            </a>
                        </div>
                        <div class="col-6 col-md-3">
                            <a href="index.php?page=nilai&action=input" class="btn btn-outline-success w-100">
                                <i class="bi bi-pencil-square"></i> <span class="d-none d-md-inline">Input Nilai</span>
                            </a>
                        </div>
                        <div class="col-6 col-md-3">
                            <a href="index.php?page=jadwal&action=tambah" class="btn btn-outline-warning w-100">
                                <i class="bi bi-calendar-plus"></i> <span class="d-none d-md-inline">Tambah Jadwal</span>
                            </a>
                        </div>
                        <div class="col-6 col-md-3">
                            <a href="index.php?page=laporan" class="btn btn-outline-info w-100">
                                <i class="bi bi-printer"></i> <span class="d-none d-md-inline">Cetak Laporan</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Jadwal Hari Ini & Info -->
    <div class="row g-3">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-white">
                    <h5 class="mb-0"><i class="bi bi-calendar-check text-primary me-2"></i>Jadwal Hari Ini</h5>
                </div>
                <div class="card-body">
                    <?php
                    $hari = [
                        'Sunday' => 'Minggu',
                        'Monday' => 'Senin',
                        'Tuesday' => 'Selasa',
                        'Wednesday' => 'Rabu',
                        'Thursday' => 'Kamis',
                        'Friday' => 'Jumat',
                        'Saturday' => 'Sabtu'
                    ];
                    $hariIni = $hari[date('l')];

                    $jadwal = $db->query("SELECT j.*, m.nama_mapel, k.nama_kelas, g.nama as guru 
                                          FROM jadwal j 
                                          JOIN mapel m ON j.mapel_id = m.id 
                                          JOIN kelas k ON j.kelas_id = k.id 
                                          JOIN guru g ON j.guru_id = g.id 
                                          WHERE j.hari = '$hariIni'
                                          ORDER BY j.jam_mulai");

                    if ($jadwal->num_rows > 0) {
                        echo '<div class="list-group">';
                        while ($row = $jadwal->fetch_assoc()) {
                            echo '<div class="list-group-item d-flex justify-content-between align-items-center">';
                            echo '<div>';
                            echo '<small class="text-primary">' . date('H:i', strtotime($row['jam_mulai'])) . ' - ' . date('H:i', strtotime($row['jam_selesai'])) . '</small>';
                            echo '<h6 class="mb-0">' . $row['nama_mapel'] . ' - Kelas ' . $row['nama_kelas'] . '</h6>';
                            echo '<small class="text-muted"><i class="bi bi-person"></i> ' . $row['guru'] . ' | <i class="bi bi-door-open"></i> ' . $row['ruangan'] . '</small>';
                            echo '</div>';
                            echo '<span class="badge bg-primary">' . $row['hari'] . '</span>';
                            echo '</div>';
                        }
                        echo '</div>';
                    } else {
                        echo '<p class="text-muted text-center py-4"><i class="bi bi-calendar-x"></i> Tidak ada jadwal hari ini</p>';
                    }
                    ?>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-header bg-white">
                    <h5 class="mb-0"><i class="bi bi-info-circle text-info me-2"></i>Informasi</h5>
                </div>
                <div class="card-body">
                    <div class="list-group list-group-flush">
                        <div class="list-group-item d-flex justify-content-between">
                            <span><i class="bi bi-building"></i> Sekolah</span>
                            <span class="fw-bold"><?php echo $_SESSION['sekolah']['nama']; ?></span>
                        </div>
                        <div class="list-group-item d-flex justify-content-between">
                            <span><i class="bi bi-calendar"></i> Tahun Ajaran</span>
                            <span class="fw-bold">
                                <?php
                                // Cek apakah ada data tahun ajaran di session
                                if (isset($_SESSION['tahun_ajaran'])) {
                                    echo $_SESSION['tahun_ajaran'];
                                } else {
                                    // Otomatis berdasarkan bulan
                                    $bulan = date('n');
                                    $tahun = date('Y');
                                    $ta = ($bulan >= 7) ? $tahun . '/' . ($tahun + 1) : ($tahun - 1) . '/' . $tahun;
                                    echo $ta;
                                }
                                ?>
                            </span>
                        </div>
                        <div class="list-group-item d-flex justify-content-between">
                            <span><i class="bi bi-sun"></i> Semester</span>
                            <span class="fw-bold">
                                <?php
                                // Cek apakah ada data semester di session
                                if (isset($_SESSION['semester'])) {
                                    echo $_SESSION['semester'];
                                } else {
                                    // Otomatis berdasarkan bulan
                                    $bulan = date('n');
                                    $semester = ($bulan >= 7) ? 'Ganjil' : 'Genap';
                                    echo $semester;
                                }
                                ?>
                            </span>
                        </div>
                        <div class="list-group-item d-flex justify-content-between">
                            <span><i class="bi bi-person"></i> Login sebagai</span>
                            <span class="fw-bold text-primary">
                                <?php
                                // Format role agar lebih rapi
                                $role = $_SESSION['role'];
                                if ($role == 'admin') echo 'Administrator';
                                elseif ($role == 'guru') echo 'Guru';
                                elseif ($role == 'siswa') echo 'Siswa';
                                else echo ucfirst($role);
                                ?>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>