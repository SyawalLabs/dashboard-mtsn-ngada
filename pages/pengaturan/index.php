<?php
// Cek role (hanya admin)
if ($_SESSION['role'] != 'admin') {
    $_SESSION['error'] = "Anda tidak memiliki akses ke halaman ini!";
    header("Location: index.php");
    exit();
}

// Proses update profil sekolah
if (isset($_POST['update_profil'])) {
    $nama_sekolah = $db->escape_string($_POST['nama_sekolah']);
    $npsn = $db->escape_string($_POST['npsn']);
    $alamat = $db->escape_string($_POST['alamat']);
    $kepala_sekolah = $db->escape_string($_POST['kepala_sekolah']);
    $akreditasi = $db->escape_string($_POST['akreditasi']);

    // Simpan ke session atau file config
    $_SESSION['sekolah'] = [
        'nama' => $nama_sekolah,
        'npsn' => $npsn,
        'alamat' => $alamat,
        'kepala' => $kepala_sekolah,
        'akreditasi' => $akreditasi
    ];

    $_SESSION['success'] = "Profil sekolah berhasil diperbarui!";
    header("Location: index.php?page=pengaturan");
    exit();
}

// Proses ganti password
if (isset($_POST['ganti_password'])) {
    $old_pass = md5($_POST['password_lama']);
    $new_pass = md5($_POST['password_baru']);
    $confirm_pass = md5($_POST['konfirmasi_password']);

    $user_id = $_SESSION['user_id'];
    $check = $db->query("SELECT * FROM users WHERE id = $user_id AND password = '$old_pass'");

    if ($check->num_rows == 0) {
        $_SESSION['error'] = "Password lama salah!";
    } elseif ($new_pass != $confirm_pass) {
        $_SESSION['error'] = "Konfirmasi password tidak cocok!";
    } else {
        $db->query("UPDATE users SET password = '$new_pass' WHERE id = $user_id");
        $_SESSION['success'] = "Password berhasil diubah!";
    }

    header("Location: index.php?page=pengaturan");
    exit();
}
?>

<div class="container-fluid">
    <h2 class="mb-4">Pengaturan</h2>

    <div class="row">
        <!-- Profil Sekolah -->
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-school me-2"></i>Profil Sekolah</h5>
                </div>
                <div class="card-body">
                    <form method="POST">
                        <div class="mb-3">
                            <label class="form-label">Nama Sekolah</label>
                            <input type="text" name="nama_sekolah" class="form-control"
                                value="<?php echo $_SESSION['sekolah']['nama'] ?? 'MTs Negeri Ngada'; ?>" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">NPSN</label>
                            <input type="text" name="npsn" class="form-control"
                                value="<?php echo $_SESSION['sekolah']['npsn'] ?? '12345678'; ?>" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Alamat</label>
                            <textarea name="alamat" class="form-control" rows="3" required><?php echo $_SESSION['sekolah']['alamat'] ?? 'Jl. Pendidikan No. 1, Ngada, NTT'; ?></textarea>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Kepala Sekolah</label>
                                <input type="text" name="kepala_sekolah" class="form-control"
                                    value="<?php echo $_SESSION['sekolah']['kepala'] ?? 'Dr. H. Ahmad, M.Pd'; ?>" required>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Akreditasi</label>
                                <select name="akreditasi" class="form-control">
                                    <option value="A" <?php echo ($_SESSION['sekolah']['akreditasi'] ?? '') == 'A' ? 'selected' : ''; ?>>A</option>
                                    <option value="B" <?php echo ($_SESSION['sekolah']['akreditasi'] ?? '') == 'B' ? 'selected' : ''; ?>>B</option>
                                    <option value="C" <?php echo ($_SESSION['sekolah']['akreditasi'] ?? '') == 'C' ? 'selected' : ''; ?>>C</option>
                                </select>
                            </div>
                        </div>

                        <button type="submit" name="update_profil" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Simpan Perubahan
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Ganti Password -->
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header bg-warning">
                    <h5 class="mb-0"><i class="fas fa-key me-2"></i>Ganti Password</h5>
                </div>
                <div class="card-body">
                    <form method="POST">
                        <div class="mb-3">
                            <label class="form-label">Password Lama</label>
                            <input type="password" name="password_lama" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Password Baru</label>
                            <input type="password" name="password_baru" class="form-control" required minlength="6">
                            <small class="text-muted">Minimal 6 karakter</small>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Konfirmasi Password</label>
                            <input type="password" name="konfirmasi_password" class="form-control" required>
                        </div>

                        <button type="submit" name="ganti_password" class="btn btn-warning">
                            <i class="fas fa-sync me-2"></i>Ganti Password
                        </button>
                    </form>
                </div>
            </div>

            <!-- Backup Database -->
            <div class="card mt-4">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0"><i class="fas fa-database me-2"></i>Backup & Restore</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="pages/pengaturan/backup.php" class="btn btn-info">
                            <i class="fas fa-download me-2"></i>Backup Database
                        </a>
                        <button class="btn btn-secondary" onclick="$('#restoreFile').click()">
                            <i class="fas fa-upload me-2"></i>Restore Database
                        </button>
                        <input type="file" id="restoreFile" style="display: none;" accept=".sql">
                    </div>
                </div>
            </div>
        </div>

        <!-- Manajemen User -->
        <div class="col-12 mb-4">
            <div class="card">
                <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="fas fa-users-cog me-2"></i>Manajemen User</h5>
                    <button class="btn btn-light btn-sm" data-bs-toggle="modal" data-bs-target="#tambahUserModal">
                        <i class="fas fa-plus"></i> Tambah User
                    </button>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Username</th>
                                    <th>Role</th>
                                    <th>Terdaftar</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $users = $db->query("SELECT * FROM users ORDER BY id");
                                $no = 1;
                                while ($user = $users->fetch_assoc()) {
                                ?>
                                    <tr>
                                        <td><?php echo $no++; ?></td>
                                        <td><?php echo $user['username']; ?></td>
                                        <td>
                                            <span class="badge bg-<?php echo $user['role'] == 'admin' ? 'danger' : ($user['role'] == 'walikelas' ? 'warning' : 'info'); ?>">
                                                <?php echo ucfirst($user['role']); ?>
                                            </span>
                                        </td>
                                        <td><?php echo date('d/m/Y', strtotime($user['created_at'])); ?></td>
                                        <td>
                                            <button class="btn btn-sm btn-warning" onclick="resetPassword(<?php echo $user['id']; ?>)">
                                                <i class="fas fa-key"></i>
                                            </button>
                                            <?php if ($user['id'] != $_SESSION['user_id']): ?>
                                                <button class="btn btn-sm btn-danger" onclick="hapusUser(<?php echo $user['id']; ?>)">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Tambah User -->
<div class="modal fade" id="tambahUserModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah User Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="pages/pengaturan/tambah_user.php" method="POST">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Username</label>
                        <input type="text" name="username" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" class="form-control" required minlength="6">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Role</label>
                        <select name="role" class="form-control">
                            <option value="admin">Admin</option>
                            <option value="walikelas">Wali Kelas</option>
                            <option value="guru">Guru</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function resetPassword(id) {
        Swal.fire({
            title: 'Reset Password',
            text: "Password akan direset menjadi '123456'",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, reset!'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = 'pages/pengaturan/reset_password.php?id=' + id;
            }
        });
    }

    function hapusUser(id) {
        Swal.fire({
            title: 'Hapus User',
            text: "User akan dihapus permanent!",
            icon: 'error',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, hapus!'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = 'pages/pengaturan/hapus_user.php?id=' + id;
            }
        });
    }
</script>