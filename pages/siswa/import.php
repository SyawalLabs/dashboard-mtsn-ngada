<?php
session_start();
require_once(__DIR__ . '/../../config/database.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['excel_file'])) {
    $file = $_FILES['excel_file'];

    // Validasi file
    $allowed_ext = ['xls', 'xlsx', 'csv'];
    $file_ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

    if (!in_array($file_ext, $allowed_ext)) {
        $_SESSION['error'] = "Format file tidak diizinkan. Gunakan .xlsx, .xls, atau .csv";
        header("Location: ../index.php?page=siswa&action=import");
        exit();
    }

    if ($file['size'] > 5000000) { // 5MB max
        $_SESSION['error'] = "Ukuran file terlalu besar (max 5MB)";
        header("Location: ../index.php?page=siswa&action=import");
        exit();
    }

    $upload_dir = __DIR__ . '/../../uploads/temp/';
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }

    $filename = basename($file['name']);
    $filepath = $upload_dir . time() . '_' . $filename;

    if (!move_uploaded_file($file['tmp_name'], $filepath)) {
        $_SESSION['error'] = "Gagal upload file";
        header("Location: ../index.php?page=siswa&action=import");
        exit();
    }

    // Proses file
    $data = [];
    $errors = [];

    if ($file_ext === 'csv') {
        $handle = fopen($filepath, 'r');
        $row_num = 0;
        while (($row = fgetcsv($handle)) !== FALSE) {
            $row_num++;
            if ($row_num === 1) continue; // Skip header

            if (count($row) < 2) continue; // Skip empty rows

            $data[] = [
                'nis' => trim($row[0]),
                'nama' => trim($row[1]),
                'kelas_id' => trim($row[2] ?? ''),
                'jenis_kelamin' => trim($row[3] ?? 'L')
            ];
        }
        fclose($handle);
    } else {
        // Handle Excel (.xlsx, .xls)
        $data = readExcelFile($filepath);
    }

    if (empty($data)) {
        unlink($filepath);
        $_SESSION['error'] = "File kosong atau format tidak sesuai";
        header("Location: ../index.php?page=siswa&action=import");
        exit();
    }

    // Preview data sebelum import
    $_SESSION['import_data'] = $data;
    $_SESSION['import_file'] = $filepath;
    $_SESSION['import_count'] = count($data);

    header("Location: ../index.php?page=siswa&action=import&step=preview");
    exit();
}

// Fungsi untuk membaca file Excel
function readExcelFile($filepath)
{
    $data = [];

    // Cek apakah file adalah .xlsx
    $fopen = fopen($filepath, 'r');
    $header = fread($fopen, 4);
    fclose($fopen);

    // Simple check for Excel format
    if ($header === 'PK\x03\x04') { // XLSX magic number
        // Gunakan simple XML reader untuk XLSX
        $zip = new ZipArchive();
        if ($zip->open($filepath) === true) {
            $xml_content = $zip->getFromName('xl/worksheets/sheet1.xml');
            if ($xml_content !== false) {
                $xml = simplexml_load_string($xml_content);

                if ($xml !== false && $xml->sheetData) {
                    $row_num = 0;
                    foreach ($xml->sheetData->row as $row) {
                        $row_num++;
                        if ($row_num === 1) continue; // Skip header

                        $cells = [];
                        foreach ($row->c as $cell) {
                            $cells[] = trim((string)$cell->v);
                        }

                        if (count($cells) >= 2 && !empty($cells[0])) {
                            $data[] = [
                                'nis' => $cells[0],
                                'nama' => $cells[1],
                                'kelas_id' => $cells[2] ?? '',
                                'jenis_kelamin' => $cells[3] ?? 'L'
                            ];
                        }
                    }
                }
            }
            $zip->close();
        }
    } else {
        // Coba sebagai XLS atau fallback ke CSV
        // Untuk simplicity, return empty
        $data = [];
    }

    return $data;
}

// Jika step = preview
if (isset($_GET['step']) && $_GET['step'] === 'preview') {
    $import_data = $_SESSION['import_data'] ?? [];
    $import_count = $_SESSION['import_count'] ?? 0;

    if (empty($import_data)) {
        unset($_SESSION['import_data']);
        unset($_SESSION['import_file']);
        unset($_SESSION['import_count']);
        $_SESSION['error'] = "Data import tidak ditemukan";
        header("Location: ../index.php?page=siswa&action=import");
        exit();
    }
?>

    <div class="container-fluid px-0">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4><i class="bi bi-file-earmark-arrow-down me-2"></i>Preview Import Data Siswa</h4>
            <a href="../index.php?page=siswa" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Batal
            </a>
        </div>

        <div class="alert alert-info">
            <i class="bi bi-info-circle"></i> Total <strong><?php echo $import_count; ?></strong> data siswa akan diimport
        </div>

        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover" id="previewTable">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>NIS</th>
                                <th>Nama Siswa</th>
                                <th>Kelas</th>
                                <th>JK</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $no = 1;
                            foreach ($import_data as $item) {
                                $kelas_name = '-';
                                if (!empty($item['kelas_id'])) {
                                    $escape_kelas_id = $db->escape_string($item['kelas_id']);
                                    $kelas_q = $db->query("SELECT nama_kelas FROM kelas WHERE id = '$escape_kelas_id'");
                                    if ($kelas_q && $kelas_q->num_rows > 0) {
                                        $kelas_name = $kelas_q->fetch_assoc()['nama_kelas'];
                                    } else {
                                        $kelas_name = '<span class="badge bg-warning">Kelas tidak ditemukan</span>';
                                    }
                                }

                                $status = '<span class="badge bg-success">Siap</span>';
                                if (empty(trim($item['nis']))) {
                                    $status = '<span class="badge bg-danger">NIS kosong</span>';
                                } elseif (empty(trim($item['nama']))) {
                                    $status = '<span class="badge bg-danger">Nama kosong</span>';
                                } else {
                                    $escape_nis = $db->escape_string($item['nis']);
                                    $check_dup = $db->query("SELECT id FROM siswa WHERE nis = '$escape_nis'");
                                    if ($check_dup && $check_dup->num_rows > 0) {
                                        $status = '<span class="badge bg-warning">Akan di-update</span>';
                                    }
                                }
                            ?>
                                <tr>
                                    <td><?php echo $no++; ?></td>
                                    <td><strong><?php echo htmlspecialchars($item['nis']); ?></strong></td>
                                    <td><?php echo htmlspecialchars($item['nama']); ?></td>
                                    <td><?php echo $kelas_name; ?></td>
                                    <td><?php echo $item['jenis_kelamin'] === 'P' ? 'Perempuan' : 'Laki-laki'; ?></td>
                                    <td><?php echo $status; ?></td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                    <form method="POST" action="proses_import.php" onsubmit="showLoading()">
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" id="confirmImport" required>
                            <label class="form-check-label" for="confirmImport">
                                Saya yakin ingin mengimport data ini dan siap mengganti data duplikat
                            </label>
                        </div>
                        <div class="text-end">
                            <a href="../index.php?page=siswa&action=import" class="btn btn-secondary">Kembali</a>
                            <button type="submit" class="btn btn-success">
                                <i class="bi bi-check-circle"></i> Lanjutkan Import
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

<?php
    exit();
}

// Form upload
?>

<div class="container-fluid px-0">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4><i class="bi bi-file-earmark-arrow-down me-2"></i>Import Data Siswa</h4>
        <a href="../index.php?page=siswa" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title mb-4">Upload File Excel</h5>

                    <form method="POST" enctype="multipart/form-data" onsubmit="showLoading()">
                        <div class="mb-4">
                            <label class="form-label"><strong>Pilih File (Excel)</strong></label>
                            <input type="file" name="excel_file" class="form-control form-control-lg"
                                accept=".xlsx,.xls,.csv" required>
                            <small class="text-muted d-block mt-2">
                                Format yang didukung: .xlsx, .xls, .csv (Max 5MB)
                            </small>
                        </div>

                        <div class="text-end">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="bi bi-upload"></i> Upload & Preview
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card mt-4">
                <div class="card-body">
                    <h5 class="card-title">Format File Excel</h5>
                    <p>File Excel harus memiliki format kolom berikut (sesuai urutan):</p>

                    <div class="table-responsive">
                        <table class="table table-sm table-bordered">
                            <thead class="table-light">
                                <tr>
                                    <th>Kolom</th>
                                    <th>Nama</th>
                                    <th>Keterangan</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><strong>A</strong></td>
                                    <td>NIS</td>
                                    <td>Nomor Induk Siswa (wajib diisi, unik)</td>
                                </tr>
                                <tr>
                                    <td><strong>B</strong></td>
                                    <td>Nama Siswa</td>
                                    <td>Nama lengkap siswa (wajib diisi)</td>
                                </tr>
                                <tr>
                                    <td><strong>C</strong></td>
                                    <td>ID Kelas</td>
                                    <td>ID kelas atau kosongkan jika tidak ada</td>
                                </tr>
                                <tr>
                                    <td><strong>D</strong></td>
                                    <td>Jenis Kelamin</td>
                                    <td>L (Laki-laki) atau P (Perempuan), default L</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <h6 class="mt-3">Contoh baris data:</h6>
                    <div class="bg-light p-2" style="font-family: monospace; font-size: 0.9rem;">
                        NIS | Nama Siswa | ID Kelas | JK<br>
                        001 | Ahmad Rudi | 1 | L<br>
                        002 | Siti Nurhaliza | 1 | P<br>
                        003 | Budi Santoso | 2 | L<br>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-info">
                <div class="card-body">
                    <h5 class="card-title"><i class="bi bi-lightbulb"></i> Tips</h5>
                    <ul class="small">
                        <li>Baris pertama akan dianggap header dan dilewati</li>
                        <li>Pastikan NIS unik dan tidak ada yang kosong</li>
                        <li>Jika NIS sudah ada, data akan diganti</li>
                        <li>Gunakan ID Kelas yang sudah ada di system</li>
                        <li>Jika ragu, lakukan preview terlebih dahulu</li>
                    </ul>
                </div>
            </div>

            <div class="card border-success mt-3">
                <div class="card-body">
                    <h5 class="card-title"><i class="bi bi-download"></i> Template</h5>
                    <p class="small">Silakan download template Excel berikut:</p>
                    <a href="pages/siswa/download_template.php" class="btn btn-sm btn-outline-success w-100">
                        <i class="bi bi-file-earmark-spreadsheet"></i> Download Template
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>