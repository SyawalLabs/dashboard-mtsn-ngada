<?php
include_once 'config/database.php';

$id = $_GET['id'];

$query = "DELETE FROM jadwal WHERE id = $id";

if ($db->query($query)) {
    session_start();
    $_SESSION['success'] = "Jadwal berhasil dihapus";
} else {
    session_start();
    $_SESSION['error'] = "Gagal menghapus: " . $db->conn->error;
}

header("Location: ../index.php?page=jadwal");
exit();
