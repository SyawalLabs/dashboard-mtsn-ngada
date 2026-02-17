<div class="container-fluid px-0">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4><i class="bi bi-people-fill me-2"></i>Data Siswa</h4>
        <div class="btn-group" role="group">
            <a href="index.php?page=siswa&action=import" class="btn btn-success" title="Import dari Excel">
                <i class="bi bi-file-earmark-arrow-down"></i> <span class="d-none d-md-inline">Import Excel</span>
            </a>
            <a href="index.php?page=siswa&action=tambah" class="btn btn-primary">
                <i class="bi bi-plus-circle"></i> <span class="d-none d-md-inline">Tambah Siswa</span>
            </a>
        </div>
    </div>

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
                            <th>JK</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $query = "SELECT s.*, k.nama_kelas 
                                  FROM siswa s 
                                  LEFT JOIN kelas k ON s.kelas_id = k.id 
                                  ORDER BY s.nama ASC";
                        $result = $db->query($query);
                        $no = 1;

                        while ($row = $result->fetch_assoc()) {
                        ?>
                            <tr>
                                <td><?php echo $no++; ?></td>
                                <td><?php echo $row['nis']; ?></td>
                                <td><?php echo $row['nama']; ?></td>
                                <td><?php echo $row['nama_kelas'] ?? '-'; ?></td>
                                <td><?php echo $row['jenis_kelamin']; ?></td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="index.php?page=siswa&action=edit&id=<?php echo $row['id']; ?>" class="btn btn-warning">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <button onclick="confirmDelete('index.php?page=siswa&action=hapus&id=<?php echo $row['id']; ?>')" class="btn btn-danger">
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