<?php
session_start();
require_once '../../config/database.php';

$siswa_id = isset($_GET['siswa_id']) ? (int)$_GET['siswa_id'] : 0;
$semester = isset($_GET['semester']) ? $db->escape_string($_GET['semester']) : '';
$tahun_ajaran = isset($_GET['tahun_ajaran']) ? $db->escape_string($_GET['tahun_ajaran']) : '';

if ($siswa_id <= 0 || empty($semester) || empty($tahun_ajaran)) {
    echo '<p>Parameter cetak rapor tidak lengkap.</p>';
    exit();
}

// Ambil data siswa
$siswa_q = $db->query("SELECT s.*, k.nama_kelas, k.wali_kelas 
                     FROM siswa s 
                     JOIN kelas k ON s.kelas_id = k.id 
                     WHERE s.id = $siswa_id");

if (!$siswa_q || $siswa_q->num_rows == 0) {
    echo '<p>Data siswa tidak ditemukan.</p>';
    exit();
}

$siswa = $siswa_q->fetch_assoc();

// Ambil data nilai
$nilai_q = $db->query("SELECT n.*, m.nama_mapel, m.kkm 
                     FROM nilai n 
                     JOIN mapel m ON n.mapel_id = m.id 
                     WHERE n.siswa_id = $siswa_id 
                     AND n.semester = '$semester' 
                     AND n.tahun_ajaran = '$tahun_ajaran'");

if (!$nilai_q) {
    $nilai_rows = [];
} else {
    $nilai_rows = [];
    while ($r = $nilai_q->fetch_assoc()) {
        $nilai_rows[] = $r;
    }
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rapor - <?php echo $siswa['nama']; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Times New Roman', serif;
            padding: 20px;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
        }

        .school-name {
            font-size: 24px;
            font-weight: bold;
            text-transform: uppercase;
        }

        .report-title {
            font-size: 20px;
            font-weight: bold;
            margin: 20px 0;
            text-align: center;
            text-decoration: underline;
        }

        .student-info {
            margin-bottom: 20px;
            border: 1px solid #000;
            padding: 10px;
        }

        .grade-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }

        .grade-table th,
        .grade-table td {
            border: 1px solid #000;
            padding: 8px;
            text-align: center;
        }

        .grade-table th {
            background-color: #f0f0f0;
        }

        .signature {
            margin-top: 50px;
            display: flex;
            justify-content: space-between;
        }

        .signature div {
            text-align: center;
            width: 200px;
        }

        @media print {
            .no-print {
                display: none;
            }
        }
    </style>
</head>

<body>
    <div class="no-print mb-3">
        <button onclick="window.print()" class="btn btn-primary">
            <i class="fas fa-print"></i> Cetak
        </button>
        <button onclick="window.close()" class="btn btn-secondary">
            Tutup
        </button>
    </div>

    <div class="header">
        <div class="school-name">MTs NEGERI NGADA</div>
        <div>Jl. Pendidikan No. 1, Ngada, NTT</div>
        <div>NPSN: 12345678 | Akreditasi: A</div>
    </div>

    <div class="report-title">
        LAPORAN HASIL BELAJAR SISWA (RAPOR)<br>
        Semester <?php echo $semester; ?> Tahun Ajaran <?php echo $tahun_ajaran; ?>
    </div>

    <div class="student-info row">
        <div class="col-md-6">
            <table width="100%">
                <tr>
                    <td width="120">Nama Siswa</td>
                    <td>: <?php echo $siswa['nama']; ?></td>
                </tr>
                <tr>
                    <td>NIS/NISN</td>
                    <td>: <?php echo $siswa['nis']; ?> / <?php echo $siswa['nisn']; ?></td>
                </tr>
                <tr>
                    <td>Tempat, Tgl Lahir</td>
                    <td>: <?php echo $siswa['tempat_lahir'] . ', ' . date('d/m/Y', strtotime($siswa['tanggal_lahir'])); ?></td>
                </tr>
                <tr>
                    <td>Jenis Kelamin</td>
                    <td>: <?php echo $siswa['jenis_kelamin'] == 'L' ? 'Laki-laki' : 'Perempuan'; ?></td>
                </tr>
            </table>
        </div>
        <div class="col-md-6">
            <table width="100%">
                <tr>
                    <td width="120">Kelas</td>
                    <td>: <?php echo $siswa['nama_kelas']; ?></td>
                </tr>
                <tr>
                    <td>Wali Kelas</td>
                    <td>: <?php echo $siswa['wali_kelas']; ?></td>
                </tr>
                <tr>
                    <td>Semester</td>
                    <td>: <?php echo $semester; ?></td>
                </tr>
                <tr>
                    <td>Tahun Ajaran</td>
                    <td>: <?php echo $tahun_ajaran; ?></td>
                </tr>
            </table>
        </div>
    </div>

    <table class="grade-table">
        <thead>
            <tr>
                <th>No</th>
                <th>Mata Pelajaran</th>
                <th>KKM</th>
                <th>UH</th>
                <th>UTS</th>
                <th>UAS</th>
                <th>Tugas</th>
                <th>Rata-rata</th>
                <th>Predikat</th>
                <th>Ket.</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $no = 1;
            $total_nilai = 0;
            $jumlah_mapel = 0;

            foreach ($nilai_rows as $row) {
                $uh = isset($row['uh']) ? floatval($row['uh']) : 0;
                $uts = isset($row['uts']) ? floatval($row['uts']) : 0;
                $uas = isset($row['uas']) ? floatval($row['uas']) : 0;
                $tugas = isset($row['tugas']) ? floatval($row['tugas']) : 0;

                $rata2 = ($uh + $uts + $uas + $tugas) / 4;
                $total_nilai += $rata2;
                $jumlah_mapel++;

                // Predikat
                if ($rata2 >= 90) $predikat = 'A';
                elseif ($rata2 >= 80) $predikat = 'B';
                elseif ($rata2 >= 70) $predikat = 'C';
                elseif ($rata2 >= 60) $predikat = 'D';
                else $predikat = 'E';

                $kkm = isset($row['kkm']) ? floatval($row['kkm']) : 0;
                $status = $rata2 >= $kkm ? 'Tuntas' : 'Remidi';
            ?>
                <tr>
                    <td><?php echo $no++; ?></td>
                    <td align="left"><?php echo htmlspecialchars($row['nama_mapel']); ?></td>
                    <td><?php echo htmlspecialchars($row['kkm']); ?></td>
                    <td><?php echo htmlspecialchars($row['uh']); ?></td>
                    <td><?php echo htmlspecialchars($row['uts']); ?></td>
                    <td><?php echo htmlspecialchars($row['uas']); ?></td>
                    <td><?php echo htmlspecialchars($row['tugas']); ?></td>
                    <td><strong><?php echo number_format($rata2, 2); ?></strong></td>
                    <td><?php echo $predikat; ?></td>
                    <td><?php echo $status; ?></td>
                </tr>
            <?php
            }

            $rata_rapor = $jumlah_mapel > 0 ? $total_nilai / $jumlah_mapel : 0;
            ?>
        </tbody>
    </table>

    <div class="row mt-3">
        <div class="col-md-6">
            <table class="table table-bordered">
                <tr>
                    <th>Rata-rata Rapor</th>
                    <td><?php echo number_format($rata_rapor, 2); ?></td>
                </tr>
                <tr>
                    <th>Peringkat Kelas</th>
                    <td>-</td>
                </tr>
                <tr>
                    <th>Jumlah Mapel Tuntas</th>
                    <td><?php echo $no - 1; ?> Mapel</td>
                </tr>
            </table>
        </div>
    </div>

    <div class="signature">
        <div>
            <p>Orang Tua/Wali,</p>
            <br><br><br>
            <p>(____________________)</p>
        </div>
        <div>
            <p>Wali Kelas,</p>
            <br><br><br>
            <p>(<?php echo $siswa['wali_kelas']; ?>)</p>
        </div>
        <div>
            <p>Ngada, <?php echo date('d F Y'); ?></p>
            <p>Kepala Sekolah,</p>
            <br><br><br>
            <p>(<?php echo $_SESSION['sekolah']['kepala'] ?? 'Dr. H. Ahmad, M.Pd'; ?>)</p>
        </div>
    </div>

    <div class="footer mt-5 text-center">
        <small>Dokumen ini sah dan dicetak secara elektronik</small>
    </div>

    <script>
        window.print();
    </script>
</body>

</html>