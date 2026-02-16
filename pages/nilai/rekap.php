<div class="container-fluid">
    <h2 class="mb-4">Rekap Nilai per Kelas</h2>

    <div class="card">
        <div class="card-body">
            <form method="GET" class="row g-3 mb-4">
                <input type="hidden" name="page" value="nilai">
                <input type="hidden" name="action" value="rekap">

                <div class="col-md-4">
                    <label class="form-label">Pilih Kelas</label>
                    <select name="kelas_id" class="form-control" required>
                        <option value="">- Pilih Kelas -</option>
                        <?php
                        $kelas = $db->query("SELECT * FROM kelas");
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
                    <input type="text" name="tahun_ajaran" class="form-control" value="<?php echo $_GET['tahun_ajaran'] ?? '2023/2024'; ?>" required>
                </div>

                <div class="col-12">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-search"></i> Tampilkan Rekap
                    </button>
                </div>
            </form>

            <?php if (isset($_GET['kelas_id'])): ?>
                <hr>

                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead class="table-dark">
                            <tr>
                                <th rowspan="2">No</th>
                                <th rowspan="2">NIS</th>
                                <th rowspan="2">Nama Siswa</th>
                                <th colspan="5" class="text-center">Mata Pelajaran</th>
                                <th rowspan="2">Rata-rata</th>
                                <th rowspan="2">Peringkat</th>
                            </tr>
                            <tr class="table-secondary">
                                <?php
                                $mapel = $db->query("SELECT * FROM mapel ORDER BY id");
                                $mapelList = [];
                                while ($m = $mapel->fetch_assoc()) {
                                    $mapelList[] = $m;
                                    echo "<th>{$m['nama_mapel']}</th>";
                                }
                                ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $kelas_id = $_GET['kelas_id'];
                            $semester = $_GET['semester'];
                            $tahun_ajaran = $_GET['tahun_ajaran'];

                            // Ambil semua siswa di kelas
                            $siswa = $db->query("SELECT * FROM siswa WHERE kelas_id = $kelas_id ORDER BY nama");
                            $no = 1;
                            $dataNilai = [];

                            while ($s = $siswa->fetch_assoc()) {
                                echo "<tr>";
                                echo "<td>{$no}</td>";
                                echo "<td>{$s['nis']}</td>";
                                echo "<td>{$s['nama']}</td>";

                                $totalNilai = 0;
                                $jumlahMapel = 0;

                                foreach ($mapelList as $m) {
                                    $nilai = $db->query("SELECT AVG((uh+uts+uas+tugas)/4) as rata FROM nilai 
                                                        WHERE siswa_id = {$s['id']} AND mapel_id = {$m['id']} 
                                                        AND semester = '$semester' AND tahun_ajaran = '$tahun_ajaran'");
                                    $row = $nilai->fetch_assoc();
                                    $rata = $row['rata'] ? number_format($row['rata'], 2) : '-';

                                    if ($row['rata']) {
                                        $totalNilai += $row['rata'];
                                        $jumlahMapel++;
                                    }

                                    echo "<td class='text-center'><strong>{$rata}</strong></td>";
                                }

                                $rataTotal = $jumlahMapel > 0 ? number_format($totalNilai / $jumlahMapel, 2) : '-';
                                echo "<td class='text-center'><strong>{$rataTotal}</strong></td>";
                                echo "<td class='text-center'>-</td>";
                                echo "</tr>";

                                $no++;
                            }
                            ?>
                        </tbody>
                    </table>
                </div>

                <div class="mt-3">
                    <a href="pages/laporan/rekap_nilai.php?kelas_id=<?php echo $kelas_id; ?>&semester=<?php echo $semester; ?>&tahun_ajaran=<?php echo $tahun_ajaran; ?>" class="btn btn-success" target="_blank">
                        <i class="fas fa-print"></i> Cetak Rekap
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>