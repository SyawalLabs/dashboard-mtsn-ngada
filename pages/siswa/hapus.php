<?php
session_start();

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id > 0) {
    $query = "DELETE FROM siswa WHERE id = $id";
    if ($db->query($query)) {
        $_SESSION['success'] = "Siswa berhasil dihapus";
    } else {
        $_SESSION['error'] = "Gagal: " . $db->conn->error;
    }
} else {
    $_SESSION['error'] = "ID tidak valid";
}

header("Location: ../index.php?page=siswa");
exit();
