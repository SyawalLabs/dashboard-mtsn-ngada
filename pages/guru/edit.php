<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    $nip = $db->escape_string($_POST['nip']);
    $nama = $db->escape_string($_POST['nama']);
    $mapel_id = $_POST['mapel_id'] ?: 'NULL';
    $jk = $_POST['jk'];

    $query = "UPDATE guru SET nip = '$nip', nama = '$nama', mapel_id = $mapel_id, jenis_kelamin = '$jk' 
              WHERE id = $id";

    if ($db->query($query)) {
        session_start();
        $_SESSION['success'] = "Guru berhasil diperbarui";
        header("Location: ../index.php?page=guru");
        exit();
    } else {
        session_start();
        $_SESSION['error'] = "Gagal: " . $db->conn->error;
        header("Location: ../index.php?page=guru&action=edit&id=$id");
        exit();
    }
}

?>

<?php
session_start();
$id = $_GET['id'];
$guru = $db->query("SELECT * FROM guru WHERE id = $id")->fetch_assoc();

if (!$guru) {
    $_SESSION['error'] = "Guru tidak ditemukan";
    header("Location: ../index.php?page=guru");
    exit();
}
?>

<div class="container-fluid px-0">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4><i class="bi bi-pencil me-2"></i>Edit Guru</h4>
        <a href="../index.php?page=guru" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            <form method="POST" onsubmit="showLoading()">
                <input type="hidden" name="id" value="<?php echo $guru['id']; ?>">

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">NIP <span class="text-danger">*</span></label>
                        <input type="text" name="nip" class="form-control" value="<?php echo $guru['nip']; ?>" required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                        <input type="text" name="nama" class="form-control" value="<?php echo $guru['nama']; ?>" required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Mata Pelajaran</label>
                        <select name="mapel_id" class="form-control">
                            <option value="">- Pilih Mapel -</option>
                            <?php
                            $mapel = $db->query("SELECT * FROM mapel ORDER BY nama_mapel");
                            while ($m = $mapel->fetch_assoc()) {
                                $selected = $guru['mapel_id'] == $m['id'] ? 'selected' : '';
                                echo "<option value='{$m['id']}' $selected>{$m['nama_mapel']}</option>";
                            }
                            ?>
                        </select>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Jenis Kelamin</label>
                        <select name="jk" class="form-control">
                            <option value="L" <?php echo $guru['jenis_kelamin'] == 'L' ? 'selected' : ''; ?>>Laki-laki</option>
                            <option value="P" <?php echo $guru['jenis_kelamin'] == 'P' ? 'selected' : ''; ?>>Perempuan</option>
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