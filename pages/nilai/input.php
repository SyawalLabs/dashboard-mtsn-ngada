<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $siswa_id = $_POST['siswa_id'];
    $mapel_id = $_POST['mapel_id'];
    $uh = $_POST['uh'];
    $uts = $_POST['uts'];
    $uas = $_POST['uas'];
    $tugas = $_POST['tugas'];
    $semester = $_POST['semester'];
    $tahun_ajaran = $_POST['tahun_ajaran'];

    // Cek apakah nilai sudah ada
    $check = $db->query("SELECT id FROM nilai WHERE siswa_id = $siswa_id AND mapel_id = $mapel_id AND semester = '$semester' AND tahun_ajaran = '$tahun_ajaran'");

    if ($check->num_rows > 0) {
        $_SESSION['error'] = "Nilai untuk siswa ini sudah ada!";
        header("Location: index.php?page=nilai&action=input");
        exit();
    }

    $query = "INSERT INTO nilai (siswa_id, mapel_id, uh, uts, uas, tugas, semester, tahun_ajaran) 
              VALUES ($siswa_id, $mapel_id, $uh, $uts, $uas, $tugas, '$semester', '$tahun_ajaran')";

    if ($db->query($query)) {
        $_SESSION['success'] = "Nilai berhasil diinput";
        header("Location: index.php?page=nilai");
    } else {
        $_SESSION['error'] = "Gagal menginput nilai: " . $db->conn->error;
    }
    exit();
}
?>

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Input Nilai Siswa</h2>
        <a href="index.php?page=nilai" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            <form method="POST" action="" id="formNilai">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Kelas</label>
                        <select id="kelas" class="form-control" required>
                            <option value="">- Pilih Kelas -</option>
                            <?php
                            $kelas = $db->query("SELECT * FROM kelas ORDER BY nama_kelas");
                            while ($k = $kelas->fetch_assoc()) {
                                echo "<option value='{$k['id']}'>{$k['nama_kelas']}</option>";
                            }
                            ?>
                        </select>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Mata Pelajaran</label>
                        <select name="mapel_id" id="mapel" class="form-control" required>
                            <option value="">- Pilih Mata Pelajaran -</option>
                            <?php
                            $mapel = $db->query("SELECT * FROM mapel ORDER BY nama_mapel");
                            while ($m = $mapel->fetch_assoc()) {
                                echo "<option value='{$m['id']}'>{$m['nama_mapel']}</option>";
                            }
                            ?>
                        </select>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Semester</label>
                        <select name="semester" class="form-control" required>
                            <option value="">- Pilih Semester -</option>
                            <option value="Ganjil">Ganjil</option>
                            <option value="Genap">Genap</option>
                        </select>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Tahun Ajaran</label>
                        <input type="text" name="tahun_ajaran" class="form-control" value="2023/2024" required>
                    </div>
                </div>

                <hr>

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
                            <!-- Data siswa akan di-load via AJAX -->
                            <tr>
                                <td colspan="7" class="text-center text-muted">
                                    Pilih kelas terlebih dahulu
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="text-end mt-3">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Simpan Semua Nilai
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('#kelas').change(function() {
            var kelas_id = $(this).val();
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

        $('#mapel').change(function() {
            $('#kelas').trigger('change');
        });
    });
</script>