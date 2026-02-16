<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Data Siswa</h2>
        <a href="index.php?page=siswa&action=tambah" class="btn btn-primary">
            <i class="fas fa-plus"></i> Tambah Siswa
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            <table id="datatable" class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>NIS</th>
                        <th>NISN</th>
                        <th>Nama</th>
                        <th>Kelas</th>
                        <th>Jenis Kelamin</th>
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
                            <td><?php echo $row['nisn']; ?></td>
                            <td><?php echo $row['nama']; ?></td>
                            <td><?php echo $row['nama_kelas'] ?? '-'; ?></td>
                            <td><?php echo $row['jenis_kelamin']; ?></td>
                            <td>
                                <a href="index.php?page=siswa&action=edit&id=<?php echo $row['id']; ?>" class="btn btn-sm btn-warning">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <button onclick="confirmDelete('index.php?page=siswa&action=hapus&id=<?php echo $row['id']; ?>')" class="btn btn-sm btn-danger">
                                    <i class="fas fa-trash"></i>
                                </button>
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