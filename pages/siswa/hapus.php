<?php
// pages/siswa/hapus.php

session_start(); // HARUS paling atas sebelum output apapun

// Tentukan root path (untuk include file)
$root_path = dirname(dirname(__DIR__)); // naik 3 level

require_once $root_path . '/config/database.php';

// Cek login
if (!isset($_SESSION['username'])) {
    header("Location: /dashboard-mtsn-ngada/login.php");
    exit();
}

// Ambil ID dari URL
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id > 0) {

    // Prepared statement (AMAN dari SQL Injection)
    $stmt = $db->prepare("DELETE FROM siswa WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        $_SESSION['success'] = "Data siswa berhasil dihapus";
    } else {
        $_SESSION['error'] = "Gagal menghapus data";
    }

    $stmt->close();
} else {
    $_SESSION['error'] = "ID siswa tidak valid";
}

// Redirect kembali ke index
header("Location: index.php?page=siswa");
exit();
