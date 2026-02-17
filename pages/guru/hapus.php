<?php
session_start();

$id = $_GET['id'];

$query = "DELETE FROM guru WHERE id = $id";

if ($db->query($query)) {
    $_SESSION['success'] = "Guru berhasil dihapus";
} else {
    $_SESSION['error'] = "Gagal: " . $db->conn->error;
}

header("Location: ../index.php?page=guru");
exit();
