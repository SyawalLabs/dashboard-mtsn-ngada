<?php
include '../../config/database.php';

$id = $_GET['id'];

$query = "DELETE FROM nilai WHERE id = $id";

if ($db->query($query)) {
    session_start();
    $_SESSION['success'] = "Nilai berhasil dihapus";
} else {
    session_start();
    $_SESSION['error'] = "Gagal menghapus: " . $db->conn->error;
}

header("Location: ../index.php?page=nilai");
exit();
