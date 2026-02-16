<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    include '../../config/database.php';

    $hari = $_POST['hari'];
    $jam_mulai = $_POST['jam_mulai'];
    $jam_selesai = $_POST['jam_selesai'];
    $mapel_id = $_POST['mapel_id'];
    $kelas_id = $_POST['kelas_id'];
    $guru_id = $_POST['guru_id'];
    $ruangan = $db->escape_string($_POST['ruangan']);

    $query = "INSERT INTO jadwal (hari, jam_mulai, jam_selesai, mapel_id, kelas_id, guru_id, ruangan) 
              VALUES ('$hari', '$jam_mulai', '$jam_selesai', $mapel_id, $kelas_id, $guru_id, '$ruangan')";

    if ($db->query($query)) {
        session_start();
        $_SESSION['success'] = "Jadwal berhasil ditambahkan";
        header("Location: ../index.php?page=jadwal");
        exit();
    } else {
        session_start();
        $_SESSION['error'] = "Gagal: " . $db->conn->error;
        header("Location: ../index.php?page=jadwal&action=tambah");
        exit();
    }
}

include '../../config/database.php';
include '../../includes/header.php';
include '../../includes/sidebar.php';
?>

<div class="container-fluid px-0">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4><i class="bi bi-calendar-plus me-2"></i>Tambah Jadwal</h4>
        <a href="index.php?page=jadwal" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            <form method="POST" onsubmit="showLoading()">
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Hari <span class="text-danger">*</span></label>
                        <select name="hari" class="form-control" required>
                            <option value="">Pilih Hari</option>
                            <option value="Senin">Senin</option>
                            <option value="Selasa">Selasa</option>
                            <option value="Rabu">Rabu</option>
                            <option value="Kamis">Kamis</option>
                            <option value="Jumat">Jumat</option>
                            <option value="Sabtu">Sabtu</option>
                        </select>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label">Jam Mulai <span class="text-danger">*</span></label>
                        <input type="time" name="jam_mulai" class="form-control" required>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label">Jam Selesai <span class="text-danger">*</span></label>
                        <input type="time" name="jam_selesai" class="form-control" required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Mata Pelajaran <span class="text-danger">*</span></label>
                        <select name="mapel_id" class="form-control" required>
                            <option value="">Pilih Mapel</option>
                            <?php
                            $mapel = $db->query("SELECT * FROM mapel ORDER BY nama_mapel");
                            while ($m = $mapel->fetch_assoc()) {
                                echo "<option value='{$m['id']}'>{$m['nama_mapel']}</option>";
                            }
                            ?>
                        </select>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Kelas <span class="text-danger">*</span></label>
                        <select name="kelas_id" class="form-control" required>
                            <option value="">Pilih Kelas</option>
                            <?php
                            $kelas = $db->query("SELECT * FROM kelas ORDER BY nama_kelas");
                            while ($k = $kelas->fetch_assoc()) {
                                echo "<option value='{$k['id']}'>{$k['nama_kelas']}</option>";
                            }
                            ?>
                        </select>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Guru <span class="text-danger">*</span></label>
                        <select name="guru_id" class="form-control" required>
                            <option value="">Pilih Guru</option>
                            <?php
                            $guru = $db->query("SELECT * FROM guru ORDER BY nama");
                            while ($g = $guru->fetch_assoc()) {
                                echo "<option value='{$g['id']}'>{$g['nama']}</option>";
                            }
                            ?>
                        </select>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Ruangan</label>
                        <input type="text" name="ruangan" class="form-control" placeholder="Contoh: R.101">
                    </div>
                </div>

                <div class="text-end">
                    <button type="reset" class="btn btn-secondary">Reset</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include '../../includes/footer.php'; ?>