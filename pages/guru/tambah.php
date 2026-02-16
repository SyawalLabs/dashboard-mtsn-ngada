<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nip = $db->escape_string($_POST['nip']);
    $nama = $db->escape_string($_POST['nama']);
    $mapel_id = $_POST['mapel_id'] ?: 'NULL';
    $jk = $_POST['jk'];

    $query = "INSERT INTO guru (nip, nama, mapel_id, jenis_kelamin) 
              VALUES ('$nip', '$nama', $mapel_id, '$jk')";

    if ($db->query($query)) {
        session_start();
        $_SESSION['success'] = "Guru berhasil ditambahkan";
        header("Location: ../index.php?page=guru");
        exit();
    } else {
        session_start();
        $_SESSION['error'] = "Gagal: " . $db->conn->error;
        header("Location: ../index.php?page=guru&action=tambah");
        exit();
    }
}

?>

<div class="container-fluid px-0">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4><i class="bi bi-person-plus me-2"></i>Tambah Guru</h4>
        <a href="../index.php?page=guru" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            <form method="POST" onsubmit="showLoading()">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">NIP <span class="text-danger">*</span></label>
                        <input type="text" name="nip" class="form-control" required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                        <input type="text" name="nama" class="form-control" required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Mata Pelajaran</label>
                        <select name="mapel_id" class="form-control">
                            <option value="">- Pilih Mapel -</option>
                            <?php
                            $mapel = $db->query("SELECT * FROM mapel ORDER BY nama_mapel");
                            while ($m = $mapel->fetch_assoc()) {
                                echo "<option value='{$m['id']}'>{$m['nama_mapel']}</option>";
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