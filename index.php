<?php
include_once 'config/database.php';

$page = isset($_GET['page']) ? $_GET['page'] : 'dashboard';
$action = isset($_GET['action']) ? $_GET['action'] : '';

// Cek file exist dan process jika POST (sebelum send header)
$base_path = 'pages/';
$file_path = '';

if ($action) {
    $file_path = $base_path . $page . '/' . $action . '.php';
} else {
    $file_path = $base_path . $page . '/index.php';
}

// Process form submission PERTAMA KALI (SEBELUM header.php)
// Gunakan ob_start untuk capture semua output dari POST handler
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (file_exists($file_path)) {
        ob_start();
        include $file_path;
        ob_end_clean();
        // Jika handler tidak exit, script tetap lanjut - tapi output sudah di-discard
    }
    // Jika POST tapi file tidak exist, skip include
}

// Include header dan sidebar HANYA untuk display (GET requests)
include 'includes/header.php';
include 'includes/sidebar.php';

// Display content HANYA untuk GET requests
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
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
}

include 'includes/footer.php';
