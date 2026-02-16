<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    include '../../config/database.php';

    $id = $_POST['id'];
    $uh = $_POST['uh'];
    $uts = $_POST['uts'];
    $uas = $_POST['uas'];
    $tugas = $_POST['tugas'] ?? 0;

    $query = "UPDATE nilai SET uh = $uh, uts = $uts, uas = $uas, tugas = $tugas WHERE id = $id";

    if ($db->query($query)) {
        session_start();
        $_SESSION['success'] = "Nilai berhasil diperbarui";
        header("Location: ../index.php?page=nilai");
        exit();
    } else {
        session_start();
        $_SESSION['error'] = "Gagal: " . $db->conn->error;
        header("Location: ../index.php?page=nilai&action=edit&id=$id");
        exit();
    }
}

include '../../config/database.php';
include '../includes/header.php';
include '../includes/sidebar.php';

$id = $_GET['id'];
$nilai = $db->query("SELECT n.*, s.nama as nama_siswa, s.nis, m.nama_mapel FROM nilai n 
                     JOIN siswa s ON n.siswa_id = s.id 
                     JOIN mapel m ON n.mapel_id = m.id 
                     WHERE n.id = $id")->fetch_assoc();

if (!$nilai) {
    $_SESSION['error'] = "Nilai tidak ditemukan";
    header("Location: ../index.php?page=nilai");
    exit();
}
?>

<div class="container-fluid px-0">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4><i class="bi bi-pencil me-2"></i>Edit Nilai</h4>
        <a href="index.php?page=nilai" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="row mb-4 p-3 bg-light rounded">
                <div class="col-md-6">
                    <h6 class="text-muted">Siswa</h6>
                    <p class="mb-0"><strong><?php echo $nilai['nama_siswa']; ?></strong> (<?php echo $nilai['nis']; ?>)</p>
                </div>
                <div class="col-md-6">
                    <h6 class="text-muted">Mata Pelajaran</h6>
                    <p class="mb-0"><strong><?php echo $nilai['nama_mapel']; ?></strong></p>
                </div>
            </div>

            <form method="POST" onsubmit="showLoading()">
                <input type="hidden" name="id" value="<?php echo $nilai['id']; ?>">

                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label">UH (Ulangan Harian) <span class="text-danger">*</span></label>
                        <input type="number" name="uh" class="form-control" value="<?php echo $nilai['uh']; ?>" step="0.01" min="0" max="100" required>
                        <small class="text-muted">0 - 100</small>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label">UTS (Ujian Tengah Semester) <span class="text-danger">*</span></label>
                        <input type="number" name="uts" class="form-control" value="<?php echo $nilai['uts']; ?>" step="0.01" min="0" max="100" required>
                        <small class="text-muted">0 - 100</small>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label">UAS (Ujian Akhir Semester) <span class="text-danger">*</span></label>
                        <input type="number" name="uas" class="form-control" value="<?php echo $nilai['uas']; ?>" step="0.01" min="0" max="100" required>
                        <small class="text-muted">0 - 100</small>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label">Tugas</label>
                        <input type="number" name="tugas" class="form-control" value="<?php echo $nilai['tugas'] ?? ''; ?>" step="0.01" min="0" max="100">
                        <small class="text-muted">0 - 100 (opsional)</small>
                    </div>

                    <div class="col-md-12 mb-3">
                        <div class="alert alert-info">
                            <strong>Rata-rata:</strong> <span id="rataRata"><?php
                                                                            $total = ($nilai['uh'] + $nilai['uts'] + $nilai['uas'] + ($nilai['tugas'] ?? 0)) / 4;
                                                                            echo number_format($total, 2);
                                                                            ?></span>
                        </div>
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

<script>
    function updateRataRata() {
        const uh = parseFloat(document.querySelector('input[name="uh"]').value) || 0;
        const uts = parseFloat(document.querySelector('input[name="uts"]').value) || 0;
        const uas = parseFloat(document.querySelector('input[name="uas"]').value) || 0;
        const tugas = parseFloat(document.querySelector('input[name="tugas"]').value) || 0;
        const rata2 = (uh + uts + uas + tugas) / 4;
        document.getElementById('rataRata').textContent = rata2.toFixed(2);
    }

    document.querySelectorAll('input[name="uh"], input[name="uts"], input[name="uas"], input[name="tugas"]').forEach(input => {
        input.addEventListener('input', updateRataRata);
    });
</script>

<?php include '../../includes/footer.php'; ?>