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
        // POST handler harus exit/redirect, tapi output sudah di-discard
    }
    // Script execution should stop here if handler called exit()
    // Jika tidak, skip include header dan langsung keluar
    exit();
}

// Process GET action handlers (SEBELUM header.php)
// Ini memastikan file action seperti 'hapus' dapat melakukan header() redirect
if ($_SERVER['REQUEST_METHOD'] == 'GET' && $action) {
    if (file_exists($file_path)) {
        // Jalankan aksi tanpa mengeluarkan output sehingga header() bisa dipanggil
        ob_start();
        @include $file_path;
        ob_end_clean();
        // Jika file action melakukan redirect/exit, eksekusi sudah berhenti.
        // Jika tidak, fall through ke tampilan normal.
    }
}

// Include header dan sidebar HANYA untuk GET display requests
// (bukan POST, dan juga bukan GET actions yang sudah exit)
include 'includes/header.php';
include 'includes/sidebar.php';

// Display content
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
