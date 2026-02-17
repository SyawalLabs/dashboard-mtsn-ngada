<?php
// Generate CSV template
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename="template_siswa.csv"');
header('Pragma: no-cache');
header('Expires: 0');

$output = fopen('php://output', 'w');

// Add BOM for UTF-8
fprintf($output, chr(0xEF) . chr(0xBB) . chr(0xBF));

// Header
fputcsv($output, ['NIS', 'Nama Siswa', 'ID Kelas', 'Jenis Kelamin']);

// Example rows
fputcsv($output, ['001', 'Ahmad Rudi', '1', 'L']);
fputcsv($output, ['002', 'Siti Nurhaliza', '1', 'P']);
fputcsv($output, ['003', 'Budi Santoso', '2', 'L']);
fputcsv($output, ['004', 'Eka Putri Sari', '2', 'P']);

fclose($output);
exit();
exit();
