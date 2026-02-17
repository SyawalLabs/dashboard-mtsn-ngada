<?php
session_start();

// Validasi session data
if (!isset($_SESSION['import_data']) || !isset($_SESSION['import_file'])) {
    $_SESSION['error'] = "Data import tidak valid";
    header("Location: ../index.php?page=siswa");
    exit();
}

require_once(__DIR__ . '/../../config/database.php');

$import_data = $_SESSION['import_data'];
$import_file = $_SESSION['import_file'];
$success_count = 0;
$error_count = 0;
$errors = [];

foreach ($import_data as $index => $item) {
    // Validasi data
    $nis = trim($item['nis']);
    $nama = trim($item['nama']);
    $kelas_id = trim($item['kelas_id'] ?? '');
    $jk = trim($item['jenis_kelamin'] ?? 'L');

    if (empty($nis) || empty($nama)) {
        $error_count++;
        $errors[] = "Baris " . ($index + 2) . ": NIS atau Nama kosong";
        continue;
    }

    // Escape data
    $nis = $db->escape_string($nis);
    $nama = $db->escape_string($nama);
    $jk = in_array($jk, ['L', 'P']) ? $jk : 'L';
    $kelas_id_sql = empty($kelas_id) ? 'NULL' : (int)$kelas_id;

    // Cek apakah NIS sudah ada
    $check = $db->query("SELECT id FROM siswa WHERE nis = '$nis'");

    if ($check && $check->num_rows > 0) {
        // Update data yang sudah ada
        $query = "UPDATE siswa SET nama = '$nama', kelas_id = $kelas_id_sql, jenis_kelamin = '$jk' 
                  WHERE nis = '$nis'";
    } else {
        // Insert data baru
        $query = "INSERT INTO siswa (nis, nama, kelas_id, jenis_kelamin) 
                  VALUES ('$nis', '$nama', $kelas_id_sql, '$jk')";
    }

    if ($db->query($query)) {
        $success_count++;
    } else {
        $error_count++;
        $errors[] = "Baris " . ($index + 2) . ": " . $db->conn->error;
    }
}

// Hapus file upload
if (file_exists($import_file)) {
    unlink($import_file);
}

// Hapus session data
unset($_SESSION['import_data']);
unset($_SESSION['import_file']);
unset($_SESSION['import_count']);

// Set pesan
if ($error_count > 0) {
    $_SESSION['warning'] = "Import selesai! $success_count data berhasil, $error_count data gagal.";
    if (count($errors) > 0) {
        $_SESSION['error_details'] = $errors;
    }
} else {
    $_SESSION['success'] = "Import selesai! $success_count data siswa berhasil diimport";
}

header("Location: ../index.php?page=siswa");
exit();
