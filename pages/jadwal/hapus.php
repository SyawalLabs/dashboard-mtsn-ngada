<?php
// pages/jadwal/hapus.php
// Hanya proses hapus lalu redirect. Tidak boleh mengeluarkan output.
session_start();
include_once 'config/database.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id > 0) {
    $query = "DELETE FROM jadwal WHERE id = $id";
    if ($db->query($query)) {
        $_SESSION['success'] = "Data jadwal berhasil dihapus";
    } else {
        $_SESSION['error'] = "Gagal menghapus data";
    }
}

header("Location: index.php?page=jadwal");
exit();
