<div class="container-fluid px-0">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4><i class="bi bi-star-fill me-2"></i>Data Nilai</h4>
        <div>
            <a href="index.php?page=nilai&action=input" class="btn btn-success">
                <i class="bi bi-plus-circle"></i> Input Nilai
            </a>
            <a href="index.php?page=nilai&action=rekap" class="btn btn-info">
                <i class="bi bi-file-text"></i> Rekap
            </a>
        </div>
    </div>

    <!-- Filter -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" class="row g-3">
                <input type="hidden" name="page" value="nilai">

                <div class="col-md-3">
                    <select name="kelas_id" class="form-control">
                        <option value="">Semua Kelas</option>
                        <?php
                        $kelas = $db->query("SELECT * FROM kelas");
                        while ($k = $kelas->fetch_assoc()) {
                            $selected = ($_GET['kelas_id'] ?? '') == $k['id'] ? 'selected' : '';
                            echo "<option value='{$k['id']}' $selected>{$k['nama_kelas']}</option>";
                        }
                        ?>
                    </select>
                </div>

                <div class="col-md-3">
                    <select name="mapel_id" class="form-control">
                        <option value="">Semua Mapel</option>
                        <?php
                        $mapel = $db->query("SELECT * FROM mapel");
                        while ($m = $mapel->fetch_assoc()) {
                            $selected = ($_GET['mapel_id'] ?? '') == $m['id'] ? 'selected' : '';
                            echo "<option value='{$m['id']}' $selected>{$m['nama_mapel']}</option>";
                        }
                        ?>
                    </select>
                </div>

                <div class="col-md-2">
                    <select name="semester" class="form-control">
                        <option value="">Semester</option>
                        <option value="Ganjil" <?php echo ($_GET['semester'] ?? '') == 'Ganjil' ? 'selected' : ''; ?>>Ganjil</option>
                        <option value="Genap" <?php echo ($_GET['semester'] ?? '') == 'Genap' ? 'selected' : ''; ?>>Genap</option>
                    </select>
                </div>

                <div class="col-md-2">
                    <input type="text" name="tahun_ajaran" class="form-control" placeholder="Tahun Ajaran" value="<?php echo $_GET['tahun_ajaran'] ?? '2024/2025'; ?>">
                </div>

                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-filter"></i> Filter
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Tabel Nilai -->
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover" id="dataTable">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>NIS</th>
                            <th>Nama</th>
                            <th>Kelas</th>
                            <th>Mapel</th>
                            <th>UH</th>
                            <th>UTS</th>
                            <th>UAS</th>
                            <th>Rata</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $where = [];
                        if (!empty($_GET['kelas_id'])) {
                            $where[] = "s.kelas_id = " . $_GET['kelas_id'];
                        }
                        if (!empty($_GET['mapel_id'])) {
                            $where[] = "n.mapel_id = " . $_GET['mapel_id'];
                        }
                        if (!empty($_GET['semester'])) {
                            $where[] = "n.semester = '" . $db->escape_string($_GET['semester']) . "'";
                        }
                        if (!empty($_GET['tahun_ajaran'])) {
                            $where[] = "n.tahun_ajaran = '" . $db->escape_string($_GET['tahun_ajaran']) . "'";
                        }

                        $where_clause = !empty($where) ? "WHERE " . implode(" AND ", $where) : "";

                        $query = "SELECT n.*, s.nis, s.nama as nama_siswa, k.nama_kelas, m.nama_mapel 
                                  FROM nilai n 
                                  JOIN siswa s ON n.siswa_id = s.id 
                                  JOIN kelas k ON s.kelas_id = k.id 
                                  JOIN mapel m ON n.mapel_id = m.id 
                                  $where_clause 
                                  ORDER BY k.nama_kelas, s.nama";

                        $result = $db->query($query);
                        $no = 1;

                        while ($row = $result->fetch_assoc()) {
                            $rata2 = ($row['uh'] + $row['uts'] + $row['uas']) / 3;
                        ?>
                            <tr>
                                <td><?php echo $no++; ?></td>
                                <td><?php echo $row['nis']; ?></td>
                                <td><?php echo $row['nama_siswa']; ?></td>
                                <td><?php echo $row['nama_kelas']; ?></td>
                                <td><?php echo $row['nama_mapel']; ?></td>
                                <td><?php echo $row['uh']; ?></td>
                                <td><?php echo $row['uts']; ?></td>
                                <td><?php echo $row['uas']; ?></td>
                                <td><strong><?php echo number_format($rata2, 2); ?></strong></td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="index.php?page=nilai&action=edit&id=<?php echo $row['id']; ?>" class="btn btn-warning">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <button onclick="confirmDelete('index.php?page=nilai&action=hapus&id=<?php echo $row['id']; ?>')" class="btn btn-danger">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
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