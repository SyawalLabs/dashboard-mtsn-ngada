<?php
session_start();

$id = $_GET['id'];

$query = "DELETE FROM mapel WHERE id = $id";

if ($db->query($query)) {
    $_SESSION['success'] = "Mata pelajaran berhasil dihapus";
} else {
    $_SESSION['error'] = "Gagal: " . $db->conn->error;
}

header("Location: ../index.php?page=mapel");
exit();
