<div class="container-fluid">
    <h2 class="mb-4"><i class="bi bi-file-pdf text-danger"></i> Cetak Rapor Siswa</h2>

    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" class="row g-3">
                <input type="hidden" name="page" value="laporan">
                <input type="hidden" name="action" value="cetak_rapor">

                <div class="col-md-4">
                    <label class="form-label">Pilih Siswa</label>
                    <select name="siswa_id" class="form-control" required>
                        <option value="">- Pilih Siswa -</option>
                        <?php
                        $siswa = $db->query("SELECT s.*, k.nama_kelas FROM siswa s LEFT JOIN kelas k ON s.kelas_id = k.id ORDER BY k.nama_kelas, s.nama");
                        while ($s = $siswa->fetch_assoc()) {
                            $selected = ($_GET['siswa_id'] ?? '') == $s['id'] ? 'selected' : '';
                            echo "<option value='{$s['id']}' $selected>{$s['nama']} ({$s['nis']}) - {$s['nama_kelas']}</option>";
                        }
                        ?>
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Semester</label>
                    <select name="semester" class="form-control" required>
                        <option value="">- Pilih Semester -</option>
                        <option value="Ganjil" <?php echo ($_GET['semester'] ?? '') == 'Ganjil' ? 'selected' : ''; ?>>Ganjil</option>
                        <option value="Genap" <?php echo ($_GET['semester'] ?? '') == 'Genap' ? 'selected' : ''; ?>>Genap</option>
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Tahun Ajaran</label>
                    <input type="text" name="tahun_ajaran" class="form-control" value="<?php echo $_GET['tahun_ajaran'] ?? '2024/2025'; ?>" required>
                </div>

                <div class="col-12">
                    <button type="submit" class="btn btn-danger">
                        <i class="bi bi-printer"></i> Tampilkan Rapor
                    </button>
                </div>
            </form>
        </div>
    </div>

    <?php if (isset($_GET['siswa_id']) && !empty($_GET['siswa_id'])): ?>
        <?php
        $siswa_id = $_GET['siswa_id'];
        $semester = $_GET['semester'];
        $tahun_ajaran = $_GET['tahun_ajaran'];

        $siswa_data = $db->query("SELECT s.*, k.nama_kelas, k.tingkat FROM siswa s LEFT JOIN kelas k ON s.kelas_id = k.id WHERE s.id = $siswa_id")->fetch_assoc();

        if ($siswa_data):
        ?>
            <div class="card" id="raporContent">
                <div class="card-body" style="background: white;">
                    <!-- Header Rapor -->
                    <div class="text-center border-bottom pb-3 mb-3">
                        <h3><?php echo $_SESSION['sekolah']['nama']; ?></h3>
                        <p class="text-muted mb-0">RAPOR PENILAIAN SISWA</p>
                        <p class="text-muted small">Semester <?php echo $semester; ?> Tahun Ajaran <?php echo $tahun_ajaran; ?></p>
                    </div>

                    <!-- Identitas Siswa -->
                    <table class="table table-borderless mb-4" style="width: 100%;">
                        <tr>
                            <td style="width: 200px;"><strong>Nama Siswa</strong></td>
                            <td>: <?php echo $siswa_data['nama']; ?></td>
                        </tr>
                        <tr>
                            <td><strong>NIS / NISN</strong></td>
                            <td>: <?php echo $siswa_data['nis']; ?> / <?php echo $siswa_data['nisn'] ?? '-'; ?></td>
                        </tr>
                        <tr>
                            <td><strong>Kelas</strong></td>
                            <td>: <?php echo $siswa_data['nama_kelas']; ?> (Tingkat <?php echo $siswa_data['tingkat']; ?>)</td>
                        </tr>
                        <tr>
                            <td><strong>Tahun Ajaran</strong></td>
                            <td>: <?php echo $tahun_ajaran; ?></td>
                        </tr>
                    </table>

                    <!-- Tabel Nilai -->
                    <h5 class="mb-3">Nilai Mata Pelajaran</h5>
                    <table class="table table-bordered table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>No</th>
                                <th>Mata Pelajaran</th>
                                <th>UH</th>
                                <th>UTS</th>
                                <th>UAS</th>
                                <th>Tugas</th>
                                <th>Rata-rata</th>
                                <th>Keterangan</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $nilai_query = $db->query("SELECT n.*, m.nama_mapel, m.kkm FROM nilai n 
                                                  JOIN mapel m ON n.mapel_id = m.id 
                                                  WHERE n.siswa_id = $siswa_id 
                                                  AND n.semester = '$semester' 
                                                  AND n.tahun_ajaran = '$tahun_ajaran'
                                                  ORDER BY m.nama_mapel");
                            $no = 1;
                            $total_rata = 0;
                            $count_mapel = 0;

                            while ($nilai_row = $nilai_query->fetch_assoc()):
                                $rata2 = ($nilai_row['uh'] + $nilai_row['uts'] + $nilai_row['uas'] + ($nilai_row['tugas'] ?? 0)) / 4;
                                $total_rata += $rata2;
                                $count_mapel++;
                                $keterangan = $rata2 >= $nilai_row['kkm'] ? '<span class="badge bg-success">Tuntas</span>' : '<span class="badge bg-danger">Belum Tuntas</span>';
                            ?>
                                <tr>
                                    <td><?php echo $no++; ?></td>
                                    <td><?php echo $nilai_row['nama_mapel']; ?></td>
                                    <td class="text-center"><?php echo $nilai_row['uh']; ?></td>
                                    <td class="text-center"><?php echo $nilai_row['uts']; ?></td>
                                    <td class="text-center"><?php echo $nilai_row['uas']; ?></td>
                                    <td class="text-center"><?php echo $nilai_row['tugas'] ?? '-'; ?></td>
                                    <td class="text-center"><strong><?php echo number_format($rata2, 2); ?></strong></td>
                                    <td><?php echo $keterangan; ?></td>
                                </tr>
                            <?php endwhile; ?>

                            <?php if ($count_mapel > 0): ?>
                                <tr class="table-light">
                                    <td colspan="6" class="text-end"><strong>Rata-rata Keseluruhan:</strong></td>
                                    <td class="text-center"><strong><?php echo number_format($total_rata / $count_mapel, 2); ?></strong></td>
                                    <td></td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>

                    <!-- Catatan -->
                    <div class="mt-4">
                        <h5>Catatan dan Informasi Tambahan</h5>
                        <div class="border p-3 rounded" style="min-height: 100px; background: #f9f9f9;">
                            <p class="text-muted" style="font-size: 0.95rem;">
                                KKM (Kriteria Ketuntasan Minimal) untuk semua mata pelajaran adalah sesuai dengan yang tertera di atas.
                                Siswa dinyatakan tuntas apabila mencapai KKM untuk setiap mata pelajaran.
                            </p>
                        </div>
                    </div>

                    <!-- Footer Rapor -->
                    <div class="row mt-5">
                        <div class="col-md-4 text-center">
                            <p>Orang Tua/Wali</p>
                            <br><br>
                            <p style="border-top: 1px solid #000; padding-top: 10px;">___________________</p>
                        </div>
                        <div class="col-md-4 text-center">
                            <p>Guru Mata Pelajaran</p>
                            <br><br>
                            <p style="border-top: 1px solid #000; padding-top: 10px;">___________________</p>
                        </div>
                        <div class="col-md-4 text-center">
                            <p>Kepala Sekolah</p>
                            <br><br>
                            <p style="border-top: 1px solid #000; padding-top: 10px;">___________________</p>
                        </div>
                    </div>

                    <div class="text-center mt-3">
                        <button class="btn btn-primary" onclick="window.print();">
                            <i class="bi bi-printer"></i> Cetak
                        </button>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    <?php endif; ?>
</div>