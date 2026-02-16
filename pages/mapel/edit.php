<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    $kode = $db->escape_string($_POST['kode']);
    $nama = $db->escape_string($_POST['nama']);
    $kkm = $_POST['kkm'];

    $query = "UPDATE mapel SET kode_mapel = '$kode', nama_mapel = '$nama', kkm = $kkm WHERE id = $id";

    if ($db->query($query)) {
        session_start();
        $_SESSION['success'] = "Mata pelajaran berhasil diperbarui";
        header("Location: ../index.php?page=mapel");
        exit();
    } else {
        session_start();
        $_SESSION['error'] = "Gagal: " . $db->conn->error;
        header("Location: ../index.php?page=mapel&action=edit&id=$id");
        exit();
    }
}

?>

<?php
session_start();
$id = $_GET['id'];
$mapel = $db->query("SELECT * FROM mapel WHERE id = $id")->fetch_assoc();

if (!$mapel) {
    $_SESSION['error'] = "Mata pelajaran tidak ditemukan";
    header("Location: ../index.php?page=mapel");
    exit();
}
?>

<div class="container-fluid px-0">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4><i class="bi bi-pencil me-2"></i>Edit Mata Pelajaran</h4>
        <a href="../index.php?page=mapel" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            <form method="POST" onsubmit="showLoading()">
                <input type="hidden" name="id" value="<?php echo $mapel['id']; ?>">

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Kode Mapel <span class="text-danger">*</span></label>
                        <input type="text" name="kode" class="form-control" value="<?php echo $mapel['kode_mapel']; ?>" required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Nama Mapel <span class="text-danger">*</span></label>
                        <input type="text" name="nama" class="form-control" value="<?php echo $mapel['nama_mapel']; ?>" required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">KKM</label>
                        <input type="number" name="kkm" class="form-control" value="<?php echo $mapel['kkm']; ?>" min="0" max="100">
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