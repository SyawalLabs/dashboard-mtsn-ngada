<?php
// Export rekap nilai ke CSV
require_once '../../config/database.php';

$kelas_id = isset($_GET['kelas_id']) ? (int)$_GET['kelas_id'] : 0;
$semester = isset($_GET['semester']) ? $db->escape_string($_GET['semester']) : '';
$tahun_ajaran = isset($_GET['tahun_ajaran']) ? $db->escape_string($_GET['tahun_ajaran']) : '';

if ($kelas_id <= 0 || empty($semester) || empty($tahun_ajaran)) {
    http_response_code(400);
    echo "Parameter tidak lengkap.";
    exit();
}

// Ambil mapel
$mapel_q = $db->query("SELECT * FROM mapel ORDER BY id");
$mapelList = [];
while ($m = $mapel_q->fetch_assoc()) {
    $mapelList[] = $m['nama_mapel'];
}

// Ambil siswa
$siswa_q = $db->query("SELECT * FROM siswa WHERE kelas_id = $kelas_id ORDER BY nama");

// Siapkan CSV
$filename = 'rekap_nilai_kelas_' . $kelas_id . '_' . date('Ymd') . '.csv';
header('Content-Type: text/csv; charset=UTF-8');
header('Content-Disposition: attachment; filename="' . $filename . '"');

$out = fopen('php://output', 'w');
// BOM untuk Excel agar UTF-8 terbaca
fwrite($out, "\xEF\xBB\xBF");

// Header CSV
$header = array_merge(['NIS', 'Nama'], $mapelList, ['Rata-rata', 'Peringkat']);
fputcsv($out, $header);

$data = [];
while ($s = $siswa_q->fetch_assoc()) {
    $row = [];
    $row[] = $s['nis'];
    $row[] = $s['nama'];

    $total = 0;
    $count = 0;
    foreach ($mapelList as $idx => $mapelNama) {
        // cari id mapel berdasarkan urutan (ambil id via query ulang) -- simpler: query by name
        $m = $db->query("SELECT id FROM mapel WHERE nama_mapel = '" . $db->escape_string($mapelNama) . "' LIMIT 1")->fetch_assoc();
        $mapel_id = $m ? (int)$m['id'] : 0;
        $nilai_q = $db->query("SELECT (uh + uts + uas + IFNULL(tugas,0))/4 as rata FROM nilai WHERE siswa_id = {$s['id']} AND mapel_id = $mapel_id AND semester = '$semester' AND tahun_ajaran = '$tahun_ajaran'");
        if ($nilai_q && $nr = $nilai_q->fetch_assoc()) {
            $r = isset($nr['rata']) ? round(floatval($nr['rata']), 1) : '';
            if ($r !== '') {
                $total += $r;
                $count++;
            }
            $row[] = $r;
        } else {
            $row[] = '';
        }
    }

    $rata = $count > 0 ? round($total / $count, 2) : '';
    $row[] = $rata;
    $data[] = $row;
}

// Hitung peringkat berdasarkan rata (naive)
usort($data, function ($a, $b) {
    $ra = isset($a[count($a) - 2]) && $a[count($a) - 2] !== '' ? floatval($a[count($a) - 2]) : 0;
    $rb = isset($b[count($b) - 2]) && $b[count($b) - 2] !== '' ? floatval($b[count($b) - 2]) : 0;
    return $rb <=> $ra;
});

// Tuliskan baris dengan peringkat
$rank = 1;
foreach ($data as $row) {
    $row[] = $rank++;
    fputcsv($out, $row);
}

fclose($out);
exit();
