<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    include '../../config/database.php';

    $nama = $db->escape_string($_POST['nama_kelas']);
    $tingkat = $db->escape_string($_POST['tingkat']);
    $wali = $db->escape_string($_POST['wali_kelas']);

    $query = "INSERT INTO kelas (nama_kelas, tingkat, wali_kelas) VALUES ('$nama', '$tingkat', '$wali')";

    if ($db->query($query)) {
        session_start();
        $_SESSION['success'] = "Kelas berhasil ditambahkan";
        header("Location: ../index.php?page=kelas");
        exit();
    } else {
        session_start();
        $_SESSION['error'] = "Gagal: " . $db->conn->error;
        header("Location: ../index.php?page=kelas&action=tambah");
        exit();
    }
}

include '../../config/database.php';
include '../../includes/header.php';
include '../../includes/sidebar.php';
?>

<div class="container-fluid px-0">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4><i class="bi bi-plus-circle me-2"></i>Tambah Kelas</h4>
        <a href="index.php?page=kelas" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            <form method="POST" onsubmit="showLoading()">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Nama Kelas <span class="text-danger">*</span></label>
                        <input type="text" name="nama_kelas" class="form-control" placeholder="Contoh: 7A" required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Tingkat</label>
                        <select name="tingkat" class="form-control">
                            <option value="VII">VII (7)</option>
                            <option value="VIII">VIII (8)</option>
                            <option value="IX">IX (9)</option>
                        </select>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Wali Kelas</label>
                        <input type="text" name="wali_kelas" class="form-control" placeholder="Nama wali kelas">
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