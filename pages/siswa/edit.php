<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    $nis = $db->escape_string($_POST['nis']);
    $nama = $db->escape_string($_POST['nama']);
    $kelas_id = $_POST['kelas_id'] ?: 'NULL';
    $jk = $_POST['jk'];

    $query = "UPDATE siswa SET nis = '$nis', nama = '$nama', kelas_id = $kelas_id, jenis_kelamin = '$jk' 
              WHERE id = $id";

    if ($db->query($query)) {
        session_start();
        $_SESSION['success'] = "Siswa berhasil diperbarui";
        header("Location: ../index.php?page=siswa");
        exit();
    } else {
        session_start();
        $_SESSION['error'] = "Gagal: " . $db->conn->error;
        header("Location: ../index.php?page=siswa&action=edit&id=$id");
        exit();
    }
}

?>

<?php
session_start();
$id = $_GET['id'];
$siswa = $db->query("SELECT * FROM siswa WHERE id = $id")->fetch_assoc();

if (!$siswa) {
    $_SESSION['error'] = "Siswa tidak ditemukan";
    header("Location: ../index.php?page=siswa");
    exit();
}
?>

<div class="container-fluid px-0">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4><i class="bi bi-pencil me-2"></i>Edit Siswa</h4>
        <a href="../index.php?page=siswa" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            <form method="POST" onsubmit="showLoading()">
                <input type="hidden" name="id" value="<?php echo $siswa['id']; ?>">

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">NIS <span class="text-danger">*</span></label>
                        <input type="text" name="nis" class="form-control" value="<?php echo $siswa['nis']; ?>" required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                        <input type="text" name="nama" class="form-control" value="<?php echo $siswa['nama']; ?>" required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Kelas</label>
                        <select name="kelas_id" class="form-control">
                            <option value="">- Pilih Kelas -</option>
                            <?php
                            $kelas = $db->query("SELECT * FROM kelas ORDER BY nama_kelas");
                            while ($k = $kelas->fetch_assoc()) {
                                $selected = $siswa['kelas_id'] == $k['id'] ? 'selected' : '';
                                echo "<option value='{$k['id']}' $selected>{$k['nama_kelas']}</option>";
                            }
                            ?>
                        </select>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Jenis Kelamin</label>
                        <select name="jk" class="form-control">
                            <option value="L" <?php echo $siswa['jenis_kelamin'] == 'L' ? 'selected' : ''; ?>>Laki-laki</option>
                            <option value="P" <?php echo $siswa['jenis_kelamin'] == 'P' ? 'selected' : ''; ?>>Perempuan</option>
                        </select>
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