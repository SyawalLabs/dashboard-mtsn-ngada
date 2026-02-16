<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Jadwal Pelajaran</h2>
        <a href="index.php?page=jadwal&action=tambah" class="btn btn-primary">
            <i class="fas fa-plus"></i> Tambah Jadwal
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            <table id="datatable" class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Hari</th>
                        <th>Jam</th>
                        <th>Mata Pelajaran</th>
                        <th>Kelas</th>
                        <th>Guru</th>
                        <th>Ruangan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $query = "SELECT j.*, m.nama_mapel, k.nama_kelas, g.nama as nama_guru 
                              FROM jadwal j 
                              JOIN mapel m ON j.mapel_id = m.id 
                              JOIN kelas k ON j.kelas_id = k.id 
                              JOIN guru g ON j.guru_id = g.id 
                              ORDER BY FIELD(j.hari, 'Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'), j.jam_mulai";
                    $result = $db->query($query);
                    $no = 1;

                    while ($row = $result->fetch_assoc()) {
                    ?>
                        <tr>
                            <td><?php echo $no++; ?></td>
                            <td><?php echo $row['hari']; ?></td>
                            <td><?php echo date('H:i', strtotime($row['jam_mulai'])) . ' - ' . date('H:i', strtotime($row['jam_selesai'])); ?></td>
                            <td><?php echo $row['nama_mapel']; ?></td>
                            <td><?php echo $row['nama_kelas']; ?></td>
                            <td><?php echo $row['nama_guru']; ?></td>
                            <td><?php echo $row['ruangan']; ?></td>
                            <td>
                                <a href="index.php?page=jadwal&action=edit&id=<?php echo $row['id']; ?>" class="btn btn-sm btn-warning">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <button onclick="confirmDelete('index.php?page=jadwal&action=hapus&id=<?php echo $row['id']; ?>')" class="btn btn-sm btn-danger">
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