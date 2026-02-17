<?php
// Laporan absensi sederhana per kelas per bulan (minimal implementation)
session_start();
require_once '../../config/database.php';

$kelas_id = isset($_GET['kelas_id']) ? (int)$_GET['kelas_id'] : 0;
$bulan = isset($_GET['bulan']) ? $_GET['bulan'] : '';

if ($kelas_id <= 0 || empty($bulan)) {
    echo '<p>Parameter tidak lengkap. Pilih kelas dan bulan.</p>';
    exit();
}

$kelas = $db->query("SELECT * FROM kelas WHERE id = $kelas_id")->fetch_assoc();
$siswa = $db->query("SELECT id, nama, nis FROM siswa WHERE kelas_id = $kelas_id ORDER BY nama");

?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Rekap Absensi - <?php echo htmlspecialchars($kelas['nama_kelas']); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container py-4">
        <h4>Rekap Absensi Kelas <?php echo htmlspecialchars($kelas['nama_kelas']); ?></h4>
        <p>Bulan: <?php echo htmlspecialchars($bulan); ?></p>
        <table class="table table-bordered table-sm">
            <thead>
                <tr>
                    <th>No</th>
                    <th>NIS</th>
                    <th>Nama</th>
                    <th>Hadir</th>
                    <th>Sakit</th>
                    <th>Izin</th>
                    <th>Alpha</th>
                </tr>
            </thead>
            <tbody>
                <?php $no = 1;
                while ($r = $siswa->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $no++; ?></td>
                        <td><?php echo htmlspecialchars($r['nis']); ?></td>
                        <td><?php echo htmlspecialchars($r['nama']); ?></td>
                        <td>-</td>
                        <td>-</td>
                        <td>-</td>
                        <td>-</td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        <div class="mt-3">
            <button class="btn btn-primary" onclick="window.print()">Cetak</button>
            <button class="btn btn-secondary" onclick="window.close()">Tutup</button>
        </div>
    </div>
</body>

</html>