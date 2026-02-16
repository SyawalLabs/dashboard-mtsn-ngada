<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    $nama = $db->escape_string($_POST['nama_kelas']);
    $tingkat = $db->escape_string($_POST['tingkat']);
    $wali = $db->escape_string($_POST['wali_kelas']);

    $query = "UPDATE kelas SET nama_kelas = '$nama', tingkat = '$tingkat', wali_kelas = '$wali' WHERE id = $id";

    if ($db->query($query)) {
        session_start();
        $_SESSION['success'] = "Kelas berhasil diperbarui";
        header("Location: ../index.php?page=kelas");
        exit();
    } else {
        session_start();
        $_SESSION['error'] = "Gagal: " . $db->conn->error;
        header("Location: ../index.php?page=kelas&action=edit&id=$id");
        exit();
    }
}

?>

<?php
session_start();
$id = $_GET['id'];
$kelas = $db->query("SELECT * FROM kelas WHERE id = $id")->fetch_assoc();

if (!$kelas) {
    $_SESSION['error'] = "Kelas tidak ditemukan";
    header("Location: ../index.php?page=kelas");
    exit();
}
?>

<div class="container-fluid px-0">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4><i class="bi bi-pencil me-2"></i>Edit Kelas</h4>
        <a href="../index.php?page=kelas" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            <form method="POST" onsubmit="showLoading()">
                <input type="hidden" name="id" value="<?php echo $kelas['id']; ?>">

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Nama Kelas <span class="text-danger">*</span></label>
                        <input type="text" name="nama_kelas" class="form-control" value="<?php echo $kelas['nama_kelas']; ?>" placeholder="Contoh: 7A" required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Tingkat</label>
                        <select name="tingkat" class="form-control">
                            <option value="VII" <?php echo $kelas['tingkat'] == 'VII' ? 'selected' : ''; ?>>VII (7)</option>
                            <option value="VIII" <?php echo $kelas['tingkat'] == 'VIII' ? 'selected' : ''; ?>>VIII (8)</option>
                            <option value="IX" <?php echo $kelas['tingkat'] == 'IX' ? 'selected' : ''; ?>>IX (9)</option>
                        </select>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Wali Kelas</label>
                        <input type="text" name="wali_kelas" class="form-control" value="<?php echo $kelas['wali_kelas'] ?? ''; ?>" placeholder="Nama wali kelas">
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