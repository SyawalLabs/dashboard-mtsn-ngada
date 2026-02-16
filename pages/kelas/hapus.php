<?php
include_once 'config/database.php';

$id = $_GET['id'];

// Update siswa yang memiliki kelas ini
$db->query("UPDATE siswa SET kelas_id = NULL WHERE kelas_id = $id");

// Hapus kelas
$query = "DELETE FROM kelas WHERE id = $id";

if ($db->query($query)) {
    session_start();
    $_SESSION['success'] = "Kelas berhasil dihapus";
} else {
    session_start();
    $_SESSION['error'] = "Gagal menghapus: " . $db->conn->error;
}

header("Location: ../index.php?page=kelas");
exit();
