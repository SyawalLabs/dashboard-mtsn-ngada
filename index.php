<?php
include 'config/database.php';
include 'includes/header.php';
include 'includes/sidebar.php';

$page = isset($_GET['page']) ? $_GET['page'] : 'dashboard';
$action = isset($_GET['action']) ? $_GET['action'] : '';

// Cek file exist
$base_path = 'pages/';
$file_path = '';

if ($action) {
    $file_path = $base_path . $page . '/' . $action . '.php';
} else {
    $file_path = $base_path . $page . '/index.php';
}

if (file_exists($file_path)) {
    include $file_path;
} else {
    // Fallback ke file sederhana
    if ($page == 'dashboard') {
        include $base_path . 'dashboard.php';
    } else {
        echo '<div class="alert alert-warning">Halaman dalam pengembangan</div>';
    }
}

include 'includes/footer.php';
