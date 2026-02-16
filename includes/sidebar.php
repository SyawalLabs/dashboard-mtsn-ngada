<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-primary fixed-top">
    <div class="container-fluid">
        <a class="navbar-brand" href="index.php">
            <i class="fas fa-school me-2"></i>Sistem Akademik
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                        <i class="fas fa-user-circle me-1"></i> <?php echo $_SESSION['username']; ?>
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="#"><i class="fas fa-user me-2"></i>Profil</a></li>
                        <li><a class="dropdown-item" href="#"><i class="fas fa-cog me-2"></i>Pengaturan</a></li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li><a class="dropdown-item text-danger" href="logout.php"><i class="fas fa-sign-out-alt me-2"></i>Logout</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>

<!-- Sidebar -->
<div class="d-flex">
    <div class="bg-dark text-white sidebar vh-100 position-fixed" style="width: 250px; margin-top: 56px;">
        <div class="p-3">
            <div class="text-center mb-4">
                <img src="assets/img/logo.png" alt="Logo" class="img-fluid rounded-circle" style="width: 80px;">
                <h5 class="mt-2">SMA Negeri 1</h5>
                <small>Akreditasi A</small>
            </div>

            <ul class="nav nav-pills flex-column">
                <li class="nav-item mb-2">
                    <a href="index.php?page=dashboard" class="nav-link text-white <?php echo ($_GET['page'] ?? 'dashboard') == 'dashboard' ? 'active bg-primary' : ''; ?>">
                        <i class="fas fa-home me-2"></i>Dashboard
                    </a>
                </li>

                <li class="nav-item mb-2">
                    <a href="index.php?page=jadwal" class="nav-link text-white <?php echo ($_GET['page'] ?? '') == 'jadwal' ? 'active bg-primary' : ''; ?>">
                        <i class="fas fa-calendar-alt me-2"></i>Jadwal
                    </a>
                </li>

                <li class="nav-item mb-2">
                    <a href="index.php?page=nilai" class="nav-link text-white <?php echo ($_GET['page'] ?? '') == 'nilai' ? 'active bg-primary' : ''; ?>">
                        <i class="fas fa-star me-2"></i>Nilai
                    </a>
                </li>

                <li class="nav-item mb-2">
                    <a href="index.php?page=siswa" class="nav-link text-white <?php echo ($_GET['page'] ?? '') == 'siswa' ? 'active bg-primary' : ''; ?>">
                        <i class="fas fa-users me-2"></i>Siswa
                    </a>
                </li>

                <li class="nav-item mb-2">
                    <a href="index.php?page=guru" class="nav-link text-white <?php echo ($_GET['page'] ?? '') == 'guru' ? 'active bg-primary' : ''; ?>">
                        <i class="fas fa-chalkboard-teacher me-2"></i>Guru
                    </a>
                </li>

                <li class="nav-item mb-2">
                    <a href="index.php?page=mapel" class="nav-link text-white <?php echo ($_GET['page'] ?? '') == 'mapel' ? 'active bg-primary' : ''; ?>">
                        <i class="fas fa-book me-2"></i>Mata Pelajaran
                    </a>
                </li>

                <li class="nav-item mb-2">
                    <a href="index.php?page=kelas" class="nav-link text-white <?php echo ($_GET['page'] ?? '') == 'kelas' ? 'active bg-primary' : ''; ?>">
                        <i class="fas fa-door-open me-2"></i>Kelas
                    </a>
                </li>

                <li class="nav-item mb-2">
                    <a href="index.php?page=laporan" class="nav-link text-white <?php echo ($_GET['page'] ?? '') == 'laporan' ? 'active bg-primary' : ''; ?>">
                        <i class="fas fa-file-alt me-2"></i>Laporan
                    </a>
                </li>

                <hr class="bg-secondary">

                <li class="nav-item mb-2">
                    <a href="logout.php" class="nav-link text-white text-danger">
                        <i class="fas fa-sign-out-alt me-2"></i>Logout
                    </a>
                </li>
            </ul>
        </div>
    </div>

    <!-- Main Content -->
    <div class="content-wrapper" style="margin-left: 250px; margin-top: 56px; width: 100%; padding: 20px;">