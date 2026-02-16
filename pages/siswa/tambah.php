<?php
// Pastikan tidak ada spasi sebelum <?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    include '../../config/database.php';

    $nis = $db->escape_string($_POST['nis']);
    $nama = $db->escape_string($_POST['nama']);
    $kelas_id = $_POST['kelas_id'] ?: 'NULL';
    $jk = $_POST['jk'];

    $query = "INSERT INTO siswa (nis, nama, kelas_id, jenis_kelamin) 
              VALUES ('$nis', '$nama', $kelas_id, '$jk')";

    if ($db->query($query)) {
        session_start();
        $_SESSION['success'] = "Siswa berhasil ditambahkan";
        header("Location: ../index.php?page=siswa");
        exit();
    } else {
        session_start();
        $_SESSION['error'] = "Gagal: " . $db->conn->error;
        header("Location: ../index.php?page=siswa&action=tambah");
        exit();
    }
}

// Jika bukan POST, tampilkan form
include '../../config/database.php';
include '../../includes/header.php';
include '../../includes/sidebar.php';
?>

<div class="container-fluid px-0">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4><i class="bi bi-person-plus me-2"></i>Tambah Siswa</h4>
        <a href="index.php?page=siswa" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            <form method="POST" onsubmit="showLoading()">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">NIS <span class="text-danger">*</span></label>
                        <input type="text" name="nis" class="form-control" required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                        <input type="text" name="nama" class="form-control" required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Kelas</label>
                        <select name="kelas_id" class="form-control">
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
                        <label class="form-label">Jenis Kelamin</label>
                        <select name="jk" class="form-control">
                            <option value="L">Laki-laki</option>
                            <option value="P">Perempuan</option>
                        </select>
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