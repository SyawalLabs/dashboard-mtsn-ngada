<?php
session_start();

$id = $_GET['id'];

// Update siswa yang memiliki kelas ini
$db->query("UPDATE siswa SET kelas_id = NULL WHERE kelas_id = $id");

// Hapus kelas
$query = "DELETE FROM kelas WHERE id = $id";

if ($db->query($query)) {
    $_SESSION['success'] = "Kelas berhasil dihapus";
} else {
    $_SESSION['error'] = "Gagal: " . $db->conn->error;
}

header("Location: ../index.php?page=kelas");
exit();
