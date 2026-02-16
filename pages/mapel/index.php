<div class="container-fluid px-0">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4><i class="bi bi-book-fill me-2"></i>Mata Pelajaran</h4>
        <a href="index.php?page=mapel&action=tambah" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> <span class="d-none d-md-inline">Tambah Mapel</span>
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover" id="dataTable">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Kode</th>
                            <th>Nama Mapel</th>
                            <th>KKM</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $query = "SELECT * FROM mapel ORDER BY nama_mapel ASC";
                        $result = $db->query($query);
                        $no = 1;

                        while ($row = $result->fetch_assoc()) {
                        ?>
                            <tr>
                                <td><?php echo $no++; ?></td>
                                <td><?php echo $row['kode_mapel']; ?></td>
                                <td><?php echo $row['nama_mapel']; ?></td>
                                <td><?php echo $row['kkm']; ?></td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="index.php?page=mapel&action=edit&id=<?php echo $row['id']; ?>" class="btn btn-warning">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <button onclick="confirmDelete('index.php?page=mapel&action=hapus&id=<?php echo $row['id']; ?>')" class="btn btn-danger">
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