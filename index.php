<?php
include 'config/database.php';
include 'includes/header.php';
include 'includes/sidebar.php';

$page = isset($_GET['page']) ? $_GET['page'] : 'dashboard';

switch ($page) {
    case 'dashboard':
        include 'pages/dashboard.php';
        break;
    case 'jadwal':
        include 'pages/jadwal/index.php';
        break;
    case 'nilai':
        include 'pages/nilai/index.php';
        break;
    case 'siswa':
        include 'pages/siswa/index.php';
        break;
    case 'guru':
        include 'pages/guru/index.php';
        break;
    case 'mapel':
        include 'pages/mapel/index.php';
        break;
    case 'kelas':
        include 'pages/kelas/index.php';
        break;
    case 'laporan':
        include 'pages/laporan/index.php';
        break;
    default:
        include 'pages/dashboard.php';
}

include 'includes/footer.php';
