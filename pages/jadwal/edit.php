<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    $hari = $_POST['hari'];
    $jam_mulai = $_POST['jam_mulai'];
    $jam_selesai = $_POST['jam_selesai'];
    $mapel_id = $_POST['mapel_id'];
    $kelas_id = $_POST['kelas_id'];
    $guru_id = $_POST['guru_id'];
    $ruangan = $db->escape_string($_POST['ruangan']);

    $query = "UPDATE jadwal SET hari = '$hari', jam_mulai = '$jam_mulai', jam_selesai = '$jam_selesai', 
              mapel_id = $mapel_id, kelas_id = $kelas_id, guru_id = $guru_id, ruangan = '$ruangan' 
              WHERE id = $id";

    if ($db->query($query)) {
        session_start();
        $_SESSION['success'] = "Jadwal berhasil diperbarui";
        header("Location: ../index.php?page=jadwal");
        exit();
    } else {
        session_start();
        $_SESSION['error'] = "Gagal: " . $db->conn->error;
        header("Location: ../index.php?page=jadwal&action=edit&id=$id");
        exit();
    }
}

?>

<?php
session_start();
$id = $_GET['id'];
$jadwal = $db->query("SELECT * FROM jadwal WHERE id = $id")->fetch_assoc();

if (!$jadwal) {
    $_SESSION['error'] = "Jadwal tidak ditemukan";
    header("Location: ../index.php?page=jadwal");
    exit();
}
?>

<div class="container-fluid px-0">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4><i class="bi bi-pencil me-2"></i>Edit Jadwal</h4>
        <a href="../index.php?page=jadwal" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            <form method="POST" onsubmit="showLoading()">
                <input type="hidden" name="id" value="<?php echo $jadwal['id']; ?>">

                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Hari <span class="text-danger">*</span></label>
                        <select name="hari" class="form-control" required>
                            <option value="">Pilih Hari</option>
                            <option value="Senin" <?php echo $jadwal['hari'] == 'Senin' ? 'selected' : ''; ?>>Senin</option>
                            <option value="Selasa" <?php echo $jadwal['hari'] == 'Selasa' ? 'selected' : ''; ?>>Selasa</option>
                            <option value="Rabu" <?php echo $jadwal['hari'] == 'Rabu' ? 'selected' : ''; ?>>Rabu</option>
                            <option value="Kamis" <?php echo $jadwal['hari'] == 'Kamis' ? 'selected' : ''; ?>>Kamis</option>
                            <option value="Jumat" <?php echo $jadwal['hari'] == 'Jumat' ? 'selected' : ''; ?>>Jumat</option>
                            <option value="Sabtu" <?php echo $jadwal['hari'] == 'Sabtu' ? 'selected' : ''; ?>>Sabtu</option>
                        </select>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label">Jam Mulai <span class="text-danger">*</span></label>
                        <input type="time" name="jam_mulai" class="form-control" value="<?php echo $jadwal['jam_mulai']; ?>" required>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label">Jam Selesai <span class="text-danger">*</span></label>
                        <input type="time" name="jam_selesai" class="form-control" value="<?php echo $jadwal['jam_selesai']; ?>" required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Mata Pelajaran <span class="text-danger">*</span></label>
                        <select name="mapel_id" class="form-control" required>
                            <option value="">Pilih Mapel</option>
                            <?php
                            $mapel = $db->query("SELECT * FROM mapel ORDER BY nama_mapel");
                            while ($m = $mapel->fetch_assoc()) {
                                $selected = $jadwal['mapel_id'] == $m['id'] ? 'selected' : '';
                                echo "<option value='{$m['id']}' $selected>{$m['nama_mapel']}</option>";
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
                                $selected = $jadwal['kelas_id'] == $k['id'] ? 'selected' : '';
                                echo "<option value='{$k['id']}' $selected>{$k['nama_kelas']}</option>";
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
                                $selected = $jadwal['guru_id'] == $g['id'] ? 'selected' : '';
                                echo "<option value='{$g['id']}' $selected>{$g['nama']}</option>";
                            }
                            ?>
                        </select>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Ruangan</label>
                        <input type="text" name="ruangan" class="form-control" value="<?php echo $jadwal['ruangan'] ?? ''; ?>" placeholder="Contoh: Ruang A1">
                    </div>
                </div>

                <div class="text-end">
                    <button type="reset" class="btn btn-secondary">Reset</button>
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>