<div class="container-fluid px-0">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4><i class="bi bi-door-open-fill me-2"></i>Data Kelas</h4>
        <a href="index.php?page=kelas&action=tambah" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> <span class="d-none d-md-inline">Tambah Kelas</span>
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover" id="dataTable">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Kelas</th>
                            <th>Tingkat</th>
                            <th>Wali Kelas</th>
                            <th>Jml Siswa</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $query = "SELECT k.*, 
                                  (SELECT COUNT(*) FROM siswa WHERE kelas_id = k.id) as jumlah_siswa 
                                  FROM kelas k 
                                  ORDER BY k.nama_kelas ASC";
                        $result = $db->query($query);
                        $no = 1;

                        while ($row = $result->fetch_assoc()) {
                        ?>
                            <tr>
                                <td><?php echo $no++; ?></td>
                                <td><?php echo $row['nama_kelas']; ?></td>
                                <td><?php echo $row['tingkat']; ?></td>
                                <td><?php echo $row['wali_kelas'] ?? '-'; ?></td>
                                <td><?php echo $row['jumlah_siswa']; ?> Siswa</td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="index.php?page=kelas&action=edit&id=<?php echo $row['id']; ?>" class="btn btn-warning">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <button onclick="confirmDelete('index.php?page=kelas&action=hapus&id=<?php echo $row['id']; ?>', 'Semua siswa di kelas ini akan kehilangan kelas!')" class="btn btn-danger">
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