<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $kode = $db->escape_string($_POST['kode']);
    $nama = $db->escape_string($_POST['nama']);
    $kkm = $_POST['kkm'];

    $query = "INSERT INTO mapel (kode_mapel, nama_mapel, kkm) VALUES ('$kode', '$nama', $kkm)";

    if ($db->query($query)) {
        session_start();
        $_SESSION['success'] = "Mata pelajaran berhasil ditambahkan";
        header("Location: ../index.php?page=mapel");
        exit();
    } else {
        session_start();
        $_SESSION['error'] = "Gagal: " . $db->conn->error;
        header("Location: ../index.php?page=mapel&action=tambah");
        exit();
    }
}

?>

<div class="container-fluid px-0">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4><i class="bi bi-plus-circle me-2"></i>Tambah Mata Pelajaran</h4>
        <a href="../index.php?page=mapel" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            <form method="POST" onsubmit="showLoading()">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Kode Mapel <span class="text-danger">*</span></label>
                        <input type="text" name="kode" class="form-control" required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Nama Mapel <span class="text-danger">*</span></label>
                        <input type="text" name="nama" class="form-control" required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">KKM</label>
                        <input type="number" name="kkm" class="form-control" value="75" min="0" max="100">
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