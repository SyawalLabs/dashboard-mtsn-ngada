<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nis = $db->escape_string($_POST['nis']);
    $nisn = $db->escape_string($_POST['nisn']);
    $nama = $db->escape_string($_POST['nama']);
    $kelas_id = $_POST['kelas_id'];
    $jk = $_POST['jenis_kelamin'];
    $tmpt_lahir = $db->escape_string($_POST['tempat_lahir']);
    $tgl_lahir = $_POST['tanggal_lahir'];
    $alamat = $db->escape_string($_POST['alamat']);
    $nama_ayah = $db->escape_string($_POST['nama_ayah']);
    $nama_ibu = $db->escape_string($_POST['nama_ibu']);

    $query = "INSERT INTO siswa (nis, nisn, nama, kelas_id, jenis_kelamin, tempat_lahir, tanggal_lahir, alamat, nama_ayah, nama_ibu) 
              VALUES ('$nis', '$nisn', '$nama', $kelas_id, '$jk', '$tmpt_lahir', '$tgl_lahir', '$alamat', '$nama_ayah', '$nama_ibu')";

    if ($db->query($query)) {
        $_SESSION['success'] = "Data siswa berhasil ditambahkan";
        header("Location: index.php?page=siswa");
    } else {
        $_SESSION['error'] = "Gagal menambahkan data: " . $db->conn->error;
        header("Location: index.php?page=siswa&action=tambah");
    }
    exit();
}
?>

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Tambah Siswa</h2>
        <a href="index.php?page=siswa" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            <form method="POST" action="">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">NIS <span class="text-danger">*</span></label>
                        <input type="text" name="nis" class="form-control" required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">NISN</label>
                        <input type="text" name="nisn" class="form-control">
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
                        <select name="jenis_kelamin" class="form-control">
                            <option value="L">Laki-laki</option>
                            <option value="P">Perempuan</option>
                        </select>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Tempat Lahir</label>
                        <input type="text" name="tempat_lahir" class="form-control">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Tanggal Lahir</label>
                        <input type="date" name="tanggal_lahir" class="form-control">
                    </div>

                    <div class="col-12 mb-3">
                        <label class="form-label">Alamat</label>
                        <textarea name="alamat" class="form-control" rows="3"></textarea>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Nama Ayah</label>
                        <input type="text" name="nama_ayah" class="form-control">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Nama Ibu</label>
                        <input type="text" name="nama_ibu" class="form-control">
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