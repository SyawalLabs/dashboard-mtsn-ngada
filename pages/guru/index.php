<div class="container-fluid px-0">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4><i class="bi bi-person-badge me-2"></i>Data Guru</h4>
        <a href="index.php?page=guru&action=tambah" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> <span class="d-none d-md-inline">Tambah Guru</span>
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover" id="dataTable">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>NIP</th>
                            <th>Nama</th>
                            <th>Mapel</th>
                            <th>JK</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $query = "SELECT g.*, m.nama_mapel 
                                  FROM guru g 
                                  LEFT JOIN mapel m ON g.mapel_id = m.id 
                                  ORDER BY g.nama ASC";
                        $result = $db->query($query);
                        $no = 1;

                        while ($row = $result->fetch_assoc()) {
                        ?>
                            <tr>
                                <td><?php echo $no++; ?></td>
                                <td><?php echo $row['nip']; ?></td>
                                <td><?php echo $row['nama']; ?></td>
                                <td><?php echo $row['nama_mapel'] ?? '-'; ?></td>
                                <td><?php echo $row['jenis_kelamin']; ?></td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="index.php?page=guru&action=edit&id=<?php echo $row['id']; ?>" class="btn btn-warning">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <button onclick="confirmDelete('index.php?page=guru&action=hapus&id=<?php echo $row['id']; ?>')" class="btn btn-danger">
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