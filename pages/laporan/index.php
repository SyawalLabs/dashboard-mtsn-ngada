<div class="container-fluid">
    <h2 class="mb-4">Laporan Akademik</h2>

    <div class="row">
        <!-- Card Cetak Rapor -->
        <div class="col-md-6 col-lg-3 mb-4">
            <div class="card h-100">
                <div class="card-body text-center">
                    <i class="fas fa-file-pdf fa-4x text-danger mb-3"></i>
                    <h5>Cetak Rapor Siswa</h5>
                    <p class="text-muted">Cetak rapor per siswa per semester</p>
                    <button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#raporModal">
                        <i class="fas fa-print me-2"></i>Cetak
                    </button>
                </div>
            </div>
        </div>

        <!-- Card Rekap Nilai -->
        <div class="col-md-6 col-lg-3 mb-4">
            <div class="card h-100">
                <div class="card-body text-center">
                    <i class="fas fa-chart-bar fa-4x text-primary mb-3"></i>
                    <h5>Rekap Nilai Kelas</h5>
                    <p class="text-muted">Rekap nilai semua siswa per kelas</p>
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#rekapModal">
                        <i class="fas fa-print me-2"></i>Cetak
                    </button>
                </div>
            </div>
        </div>

        <!-- Card Absensi -->
        <div class="col-md-6 col-lg-3 mb-4">
            <div class="card h-100">
                <div class="card-body text-center">
                    <i class="fas fa-calendar-check fa-4x text-success mb-3"></i>
                    <h5>Laporan Absensi</h5>
                    <p class="text-muted">Rekap kehadiran siswa</p>
                    <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#absensiModal">
                        <i class="fas fa-print me-2"></i>Cetak
                    </button>
                </div>
            </div>
        </div>

        <!-- Card Statistik -->
        <div class="col-md-6 col-lg-3 mb-4">
            <div class="card h-100">
                <div class="card-body text-center">
                    <i class="fas fa-chart-pie fa-4x text-info mb-3"></i>
                    <h5>Statistik Akademik</h5>
                    <p class="text-muted">Grafik perkembangan nilai</p>
                    <button class="btn btn-info" onclick="window.location.href='index.php?page=dashboard'">
                        <i class="fas fa-chart-line me-2"></i>Lihat
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Laporan Lainnya -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-secondary text-white">
                    <h5 class="mb-0">Laporan Lainnya</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <a href="#" class="text-decoration-none">
                                <div class="d-flex align-items-center p-3 border rounded">
                                    <i class="fas fa-users fa-2x text-primary me-3"></i>
                                    <div>
                                        <h6 class="mb-1">Data Siswa</h6>
                                        <small class="text-muted">Export data siswa ke Excel</small>
                                    </div>
                                </div>
                            </a>
                        </div>

                        <div class="col-md-4 mb-3">
                            <a href="#" class="text-decoration-none">
                                <div class="d-flex align-items-center p-3 border rounded">
                                    <i class="fas fa-chalkboard-teacher fa-2x text-success me-3"></i>
                                    <div>
                                        <h6 class="mb-1">Data Guru</h6>
                                        <small class="text-muted">Export data guru ke Excel</small>
                                    </div>
                                </div>
                            </a>
                        </div>

                        <div class="col-md-4 mb-3">
                            <a href="#" class="text-decoration-none">
                                <div class="d-flex align-items-center p-3 border rounded">
                                    <i class="fas fa-calendar fa-2x text-warning me-3"></i>
                                    <div>
                                        <h6 class="mb-1">Jadwal Pelajaran</h6>
                                        <small class="text-muted">Cetak jadwal per kelas</small>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Cetak Rapor -->
<div class="modal fade" id="raporModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Cetak Rapor Siswa</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="pages/laporan/cetak_rapor.php" method="GET" target="_blank">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Pilih Kelas</label>
                        <select name="kelas_id" class="form-control" required>
                            <option value="">- Pilih Kelas -</option>
                            <?php
                            $kelas = $db->query("SELECT * FROM kelas");
                            while ($k = $kelas->fetch_assoc()) {
                                echo "<option value='{$k['id']}'>{$k['nama_kelas']}</option>";
                            }
                            ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Pilih Siswa</label>
                        <select name="siswa_id" class="form-control" required>
                            <option value="">- Pilih Siswa -</option>
                        </select>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Semester</label>
                            <select name="semester" class="form-control">
                                <option value="Ganjil">Ganjil</option>
                                <option value="Genap">Genap</option>
                            </select>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Tahun Ajaran</label>
                            <input type="text" name="tahun_ajaran" class="form-control" value="2023/2024">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-print me-2"></i>Cetak
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Rekap Nilai -->
<div class="modal fade" id="rekapModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Rekap Nilai Kelas</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="pages/laporan/rekap_nilai.php" method="GET" target="_blank">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Pilih Kelas</label>
                        <select name="kelas_id" class="form-control" required>
                            <option value="">- Pilih Kelas -</option>
                            <?php
                            $kelas = $db->query("SELECT * FROM kelas");
                            while ($k = $kelas->fetch_assoc()) {
                                echo "<option value='{$k['id']}'>{$k['nama_kelas']}</option>";
                            }
                            ?>
                        </select>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Semester</label>
                            <select name="semester" class="form-control">
                                <option value="Ganjil">Ganjil</option>
                                <option value="Genap">Genap</option>
                            </select>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Tahun Ajaran</label>
                            <input type="text" name="tahun_ajaran" class="form-control" value="2023/2024">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-print me-2"></i>Cetak
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Absensi -->
<div class="modal fade" id="absensiModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Laporan Absensi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="pages/laporan/absensi.php" method="GET" target="_blank">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Pilih Kelas</label>
                        <select name="kelas_id" class="form-control" required>
                            <option value="">- Pilih Kelas -</option>
                            <?php
                            $kelas = $db->query("SELECT * FROM kelas");
                            while ($k = $kelas->fetch_assoc()) {
                                echo "<option value='{$k['id']}'>{$k['nama_kelas']}</option>";
                            }
                            ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Bulan</label>
                        <input type="month" name="bulan" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-print me-2"></i>Cetak
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // Load siswa berdasarkan kelas
    $('select[name="kelas_id"]').change(function() {
        var kelas_id = $(this).val();
        var siswaSelect = $('select[name="siswa_id"]');

        siswaSelect.html('<option value="">Loading...</option>');

        $.ajax({
            url: 'pages/laporan/get_siswa.php',
            type: 'POST',
            data: {
                kelas_id: kelas_id
            },
            success: function(response) {
                siswaSelect.html(response);
            }
        });
    });
</script>