<?php
// Jika file di-include dari index.php, $db sudah ada. Jika dibuka langsung (target _blank), siapkan DB dan session.
if (!isset($db)) {
    session_start();
    require_once '../../config/database.php';
}

?>
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
                <?php
                // Ambil daftar mapel satu kali untuk header dan perhitungan
                $mapel = $db->query("SELECT * FROM mapel ORDER BY id");
                $mapelList = [];
                while ($m = $mapel->fetch_assoc()) {
                    $mapelList[] = $m;
                }

                if (count($mapelList) == 0) {
                    echo '<div class="alert alert-warning">Data mata pelajaran belum tersedia.</div>';
                } else {
                ?>
                    <div class="d-flex justify-content-end mb-3 gap-2">
                        <a href="pages/laporan/export_rekap.php?kelas_id=<?php echo rawurlencode($kelas_id); ?>&semester=<?php echo rawurlencode($semester); ?>&tahun_ajaran=<?php echo rawurlencode($tahun_ajaran); ?>" target="_blank" class="btn btn-outline-secondary btn-sm">
                            <i class="bi bi-download"></i> Ekspor CSV
                        </a>
                        <button class="btn btn-success btn-sm" onclick="window.print()"><i class="bi bi-printer"></i> Cetak</button>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered table-hover table-sm align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>No</th>
                                    <th>NIS</th>
                                    <th>Nama Siswa</th>
                                    <th colspan="<?php echo count($mapelList); ?>" class="text-center">Mata Pelajaran</th>
                                    <th>Rata-rata</th>
                                    <th>Peringkat</th>
                                </tr>
                                <tr class="table-secondary">
                                    <?php
                                    // Tampilkan header singkatan mapel
                                    foreach ($mapelList as $m) {
                                        // gunakan title untuk nama penuh dan singkatan di tampilan
                                        $short = htmlspecialchars(substr($m['nama_mapel'], 0, 12));
                                        $full = htmlspecialchars($m['nama_mapel']);
                                        echo "<th style='text-align: center; font-size: 0.9rem;' title='{$full}'>" . $short . "</th>";
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
                                    $nilai_q = $db->query("SELECT (uh + uts + uas + IFNULL(tugas, 0)) / 4 as rata, uh, uts, uas, tugas FROM nilai 
                                                        WHERE siswa_id = {$s['id']} AND mapel_id = {$m['id']} 
                                                        AND semester = '$semester' AND tahun_ajaran = '$tahun_ajaran'");

                                    if ($nilai_q && $row = $nilai_q->fetch_assoc()) {
                                        $rata = isset($row['rata']) ? floatval($row['rata']) : 0;
                                        $nilaiPerMapel[] = $rata;

                                        if (isset($row['rata']) && $row['rata'] !== null) {
                                            $totalNilai += floatval($row['rata']);
                                            $jumlahMapel++;
                                        }
                                    } else {
                                        // Tidak ada nilai untuk mapel ini
                                        $nilaiPerMapel[] = 0;
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
                                echo "<td>" . htmlspecialchars($data['nis']) . "</td>";
                                echo "<td>" . htmlspecialchars($data['nama']) . "</td>";
                                // pastikan jumlah kolom nilai sama dengan jumlah mapel
                                foreach ($mapelList as $i => $m) {
                                    $n = isset($data['nilai'][$i]) ? $data['nilai'][$i] : 0;
                                    echo "<td style='text-align: center;'>" . ($n > 0 ? number_format($n, 1) : '-') . "</td>";
                                }
                                echo "<td><strong>" . number_format($data['rata'], 2) . "</strong></td>";
                                echo "<td style='text-align: center;'><span class='badge bg-primary'>" . ($key + 1) . "</span></td>";
                                echo "</tr>";
                            }
                        }
                            ?>
                            </tbody>
                        </table>
                    </div>
            </div>
        </div>
    <?php endif; ?>
</div>