<?php
// Mengembalikan <option> untuk select siswa berdasarkan kelas (dipanggil via AJAX)
require_once '../../config/database.php';

$kelas_id = isset($_POST['kelas_id']) ? (int)$_POST['kelas_id'] : 0;

$options = "<option value=''>- Pilih Siswa -</option>";
if ($kelas_id > 0) {
    $res = $db->query("SELECT id, nama FROM siswa WHERE kelas_id = $kelas_id ORDER BY nama");
    if ($res) {
        while ($r = $res->fetch_assoc()) {
            $options .= "<option value='" . $r['id'] . "'>" . htmlspecialchars($r['nama']) . "</option>";
        }
    }
}

echo $options;
