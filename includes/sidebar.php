<!-- Sidebar -->
<div class="sidebar" id="sidebar">
    <div class="sidebar-header">
        <h5 class="mb-0"><i class="bi bi-building"></i> MTsN Ngada</h5>
        <small class="text-white-50">Akreditasi A</small>
    </div>

    <div class="sidebar-menu">
        <?php
        $current_page = $_GET['page'] ?? 'dashboard';
        ?>
        <a href="index.php?page=dashboard" class="<?php echo $current_page == 'dashboard' ? 'active' : ''; ?>">
            <i class="bi bi-speedometer2"></i>
            <span>Dashboard</span>
        </a>

        <a href="index.php?page=jadwal" class="<?php echo $current_page == 'jadwal' ? 'active' : ''; ?>">
            <i class="bi bi-calendar-week"></i>
            <span>Jadwal</span>
        </a>

        <a href="index.php?page=nilai" class="<?php echo $current_page == 'nilai' ? 'active' : ''; ?>">
            <i class="bi bi-star-fill"></i>
            <span>Nilai</span>
        </a>

        <a href="index.php?page=siswa" class="<?php echo $current_page == 'siswa' ? 'active' : ''; ?>">
            <i class="bi bi-people-fill"></i>
            <span>Siswa</span>
        </a>

        <a href="index.php?page=guru" class="<?php echo $current_page == 'guru' ? 'active' : ''; ?>">
            <i class="bi bi-person-badge"></i>
            <span>Guru</span>
        </a>

        <a href="index.php?page=mapel" class="<?php echo $current_page == 'mapel' ? 'active' : ''; ?>">
            <i class="bi bi-book-fill"></i>
            <span>Mata Pelajaran</span>
        </a>

        <a href="index.php?page=kelas" class="<?php echo $current_page == 'kelas' ? 'active' : ''; ?>">
            <i class="bi bi-door-open-fill"></i>
            <span>Kelas</span>
        </a>

        <a href="index.php?page=laporan" class="<?php echo $current_page == 'laporan' ? 'active' : ''; ?>">
            <i class="bi bi-file-text-fill"></i>
            <span>Laporan</span>
        </a>

        <hr class="bg-secondary">

        <a href="index.php?page=pengaturan" class="<?php echo $current_page == 'pengaturan' ? 'active' : ''; ?>">
            <i class="bi bi-gear-fill"></i>
            <span>Pengaturan</span>
        </a>

        <a href="logout.php" class="text-danger">
            <i class="bi bi-box-arrow-right"></i>
            <span>Logout</span>
        </a>
    </div>

    <div class="sidebar-footer p-3 text-center text-white-50 small">
        <i class="bi bi-person-circle"></i> <?php echo $_SESSION['username']; ?> (<?php echo $_SESSION['role']; ?>)
    </div>
</div>

<!-- Main Content -->
<div class="main-content" id="mainContent">
    <!-- Navbar Top -->
    <div class="navbar-top">
        <button class="menu-toggle" id="menuToggle">
            <i class="bi bi-list"></i>
        </button>
        <h4 class="mb-0" id="pageTitle">
            <?php
            $page = $_GET['page'] ?? 'dashboard';
            echo ucfirst($page);
            ?>
        </h4>
        <div class="d-flex align-items-center gap-3">
            <span class="text-muted d-none d-md-block">
                <i class="bi bi-calendar"></i> <?php echo date('d/m/Y'); ?>
            </span>
            <div class="dropdown">
                <button class="btn btn-light dropdown-toggle" type="button" data-bs-toggle="dropdown">
                    <i class="bi bi-person-circle"></i>
                </button>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li><a class="dropdown-item" href="#"><i class="bi bi-person"></i> Profil</a></li>
                    <li><a class="dropdown-item" href="index.php?page=pengaturan"><i class="bi bi-gear"></i> Pengaturan</a></li>
                    <li>
                        <hr class="dropdown-divider">
                    </li>
                    <li><a class="dropdown-item text-danger" href="logout.php"><i class="bi bi-box-arrow-right"></i> Logout</a></li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Content Area -->
    <div id="contentArea">