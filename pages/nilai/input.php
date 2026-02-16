<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $siswa_id = $_POST['siswa_id'];
    $mapel_id = $_POST['mapel_id'];
    $uh = $_POST['uh'];
    $uts = $_POST['uts'];
    $uas = $_POST['uas'];
    $tugas = $_POST['tugas'] ?? [];
    $semester = $_POST['semester'];
    $tahun_ajaran = $_POST['tahun_ajaran'];

    $success = true;

    for ($i = 0; $i < count($siswa_id); $i++) {
        if (!empty($uh[$i]) && !empty($uts[$i]) && !empty($uas[$i])) {
            $nilai_tugas = !empty($tugas[$i]) ? $tugas[$i] : 0;

            // Cek apakah sudah ada
            $check = $db->query("SELECT id FROM nilai WHERE siswa_id = {$siswa_id[$i]} AND mapel_id = $mapel_id AND semester = '$semester' AND tahun_ajaran = '$tahun_ajaran'");

            if ($check->num_rows > 0) {
                // Update
                $query = "UPDATE nilai SET uh = {$uh[$i]}, uts = {$uts[$i]}, uas = {$uas[$i]}, tugas = $nilai_tugas 
                          WHERE siswa_id = {$siswa_id[$i]} AND mapel_id = $mapel_id AND semester = '$semester' AND tahun_ajaran = '$tahun_ajaran'";
            } else {
                // Insert
                $query = "INSERT INTO nilai (siswa_id, mapel_id, uh, uts, uas, tugas, semester, tahun_ajaran) 
                          VALUES ({$siswa_id[$i]}, $mapel_id, {$uh[$i]}, {$uts[$i]}, {$uas[$i]}, $nilai_tugas, '$semester', '$tahun_ajaran')";
            }

            if (!$db->query($query)) {
                $success = false;
            }
        }
    }

    if ($success) {
        $_SESSION['success'] = "Nilai berhasil disimpan";
    } else {
        $_SESSION['error'] = "Ada kesalahan saat menyimpan nilai";
    }

    header("Location: ../index.php?page=nilai");
    exit();
}

?>

<div class="container-fluid px-0">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4><i class="bi bi-pencil-square me-2"></i>Input Nilai</h4>
        <a href="index.php?page=nilai" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            <form method="POST" id="formNilai" onsubmit="showLoading()">
                <!-- Pilih Kelas & Mapel -->
                <div class="row g-3 mb-4">
                    <div class="col-md-4">
                        <label class="form-label">Kelas</label>
                        <select id="kelas" class="form-control" required>
                            <option value="">Pilih Kelas</option>
                            <?php
                            $kelas = $db->query("SELECT * FROM kelas ORDER BY nama_kelas");
                            while ($k = $kelas->fetch_assoc()) {
                                echo "<option value='{$k['id']}'>{$k['nama_kelas']}</option>";
                            }
                            ?>
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Mata Pelajaran</label>
                        <select name="mapel_id" id="mapel" class="form-control" required>
                            <option value="">Pilih Mapel</option>
                            <?php
                            $mapel = $db->query("SELECT * FROM mapel ORDER BY nama_mapel");
                            while ($m = $mapel->fetch_assoc()) {
                                echo "<option value='{$m['id']}'>{$m['nama_mapel']}</option>";
                            }
                            ?>
                        </select>
                    </div>

                    <div class="col-md-2">
                        <label class="form-label">Semester</label>
                        <select name="semester" class="form-control" required>
                            <option value="Ganjil">Ganjil</option>
                            <option value="Genap">Genap</option>
                        </select>
                    </div>

                    <div class="col-md-2">
                        <label class="form-label">Tahun Ajaran</label>
                        <input type="text" name="tahun_ajaran" class="form-control" value="2024/2025" required>
                    </div>
                </div>

                <!-- Daftar Siswa -->
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead class="table-light">
                            <tr>
                                <th>No</th>
                                <th>NIS</th>
                                <th>Nama Siswa</th>
                                <th>UH</th>
                                <th>UTS</th>
                                <th>UAS</th>
                                <th>Tugas</th>
                            </tr>
                        </thead>
                        <tbody id="daftarSiswa">
                            <tr>
                                <td colspan="6" class="text-center text-muted">
                                    <i class="bi bi-info-circle"></i> Pilih kelas dan mata pelajaran terlebih dahulu
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="text-end mt-3">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save"></i> Simpan Nilai
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('#kelas, #mapel').change(function() {
            var kelas_id = $('#kelas').val();
            var mapel_id = $('#mapel').val();

            if (kelas_id && mapel_id) {
                $.ajax({
                    url: 'pages/nilai/get_siswa.php',
                    type: 'POST',
                    data: {
                        kelas_id: kelas_id,
                        mapel_id: mapel_id
                    },
                    success: function(response) {
                        $('#daftarSiswa').html(response);
                    }
                });
            }
        });
    });
</script>