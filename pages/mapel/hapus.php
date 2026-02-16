<?php
include '../../config/database.php';

$id = $_GET['id'];

$query = "DELETE FROM mapel WHERE id = $id";

if ($db->query($query)) {
    session_start();
    $_SESSION['success'] = "Mata pelajaran berhasil dihapus";
} else {
    session_start();
    $_SESSION['error'] = "Gagal menghapus: " . $db->conn->error;
}

header("Location: ../index.php?page=mapel");
exit();
