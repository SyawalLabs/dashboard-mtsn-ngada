<div class="container-fluid">
    <h2 class="mb-4"><i class="bi bi-file-earmark-pdf"></i> Rekap Nilai Per Kelas</h2>

    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" class="row g-3">
                <input type="hidden" name="page" value="laporan">
                <input type="hidden" name="action" value="rekap_nilai">

                <div class="col-md-4">
                    <label class="form-label">Pilih Kelas</label>
                    <select name="kelas_id" class="form-control" required>
                        <option value="">- Pilih Kelas -</option>
                        <?php
                        $kelas = $db->query("SELECT * FROM kelas ORDER BY nama_kelas");
                        while ($k = $kelas->fetch_assoc()) {
                            $selected = ($_GET['kelas_id'] ?? '') == $k['id'] ? 'selected' : '';
                            echo "<option value='{$k['id']}' $selected>{$k['nama_kelas']}</option>";
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
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-filter"></i> Tampilkan
                    </button>
                    <button type="button" class="btn btn-success" onclick="if(document.querySelector('.table')) window.print();">
                        <i class="bi bi-printer"></i> Cetak
                    </button>
                </div>
            </form>
        </div>
    </div>

    <?php if (isset($_GET['kelas_id']) && !empty($_GET['kelas_id'])): ?>
        <?php
        $kelas_id = $_GET['kelas_id'];
        $semester = $_GET['semester'];
        $tahun_ajaran = $_GET['tahun_ajaran'];

        $kelas_data = $db->query("SELECT * FROM kelas WHERE id = $kelas_id")->fetch_assoc();
        ?>
        <div class="card">
            <div class="card-body">
                <h5 class="card-title" style="text-align: center; margin-bottom: 20px;">
                    Rekap Nilai Kelas <?php echo $kelas_data['nama_kelas']; ?><br>
                    <small class="text-muted">Semester <?php echo $semester; ?> - Tahun Ajaran <?php echo $tahun_ajaran; ?></small>
                </h5>

                <div class="table-responsive">
                    <table class="table table-bordered table-hover table-sm">
                        <thead class="table-light">
                            <tr>
                                <th>No</th>
                                <th>NIS</th>
                                <th>Nama Siswa</th>
                                <th colspan="5" class="text-center">Mata Pelajaran</th>
                                <th>Rata-rata</th>
                                <th>Peringkat</th>
                            </tr>
                            <tr class="table-secondary">
                                <?php
                                $mapel = $db->query("SELECT * FROM mapel ORDER BY id");
                                $mapelList = [];
                                while ($m = $mapel->fetch_assoc()) {
                                    $mapelList[] = $m;
                                    echo "<th style='text-align: center; font-size: 0.9rem;'>" . substr($m['nama_mapel'], 0, 10) . "</th>";
                                }
                                ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $siswa = $db->query("SELECT * FROM siswa WHERE kelas_id = $kelas_id ORDER BY nama");
                            $no = 1;
                            $dataNilai = [];

                            while ($s = $siswa->fetch_assoc()) {
                                $totalNilai = 0;
                                $jumlahMapel = 0;
                                $nilaiPerMapel = [];

                                foreach ($mapelList as $m) {
                                    $nilai = $db->query("SELECT (uh + uts + uas + IFNULL(tugas, 0)) / 4 as rata FROM nilai 
                                                        WHERE siswa_id = {$s['id']} AND mapel_id = {$m['id']} 
                                                        AND semester = '$semester' AND tahun_ajaran = '$tahun_ajaran'");
                                    $row = $nilai->fetch_assoc();
                                    $rata = $row['rata'] ? floatval($row['rata']) : 0;
                                    $nilaiPerMapel[] = $rata;

                                    if ($row['rata']) {
                                        $totalNilai += $row['rata'];
                                        $jumlahMapel++;
                                    }
                                }

                                $rataKeseluruhan = $jumlahMapel > 0 ? $totalNilai / $jumlahMapel : 0;
                                $dataNilai[] = [
                                    'nama' => $s['nama'],
                                    'nis' => $s['nis'],
                                    'nilai' => $nilaiPerMapel,
                                    'rata' => $rataKeseluruhan
                                ];
                            }

                            // Sort by rata-rata nilai descending
                            usort($dataNilai, function ($a, $b) {
                                return $b['rata'] <=> $a['rata'];
                            });

                            // Display with ranking
                            foreach ($dataNilai as $key => $data) {
                                echo "<tr>";
                                echo "<td>" . ($key + 1) . "</td>";
                                echo "<td>{$data['nis']}</td>";
                                echo "<td>{$data['nama']}</td>";
                                foreach ($data['nilai'] as $n) {
                                    echo "<td style='text-align: center;'>" . ($n > 0 ? number_format($n, 1) : '-') . "</td>";
                                }
                                echo "<td><strong>" . number_format($data['rata'], 2) . "</strong></td>";
                                echo "<td style='text-align: center;'><strong>" . ($key + 1) . "</strong></td>";
                                echo "</tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>