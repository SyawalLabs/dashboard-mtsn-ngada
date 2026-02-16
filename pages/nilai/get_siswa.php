<?php
include '../../config/database.php';

$kelas_id = $_POST['kelas_id'];
$mapel_id = $_POST['mapel_id'];

$query = "SELECT * FROM siswa WHERE kelas_id = $kelas_id ORDER BY nama";
$result = $db->query($query);
$no = 1;

while ($siswa = $result->fetch_assoc()) {
    // Cek apakah sudah ada nilai
    $check = $db->query("SELECT * FROM nilai WHERE siswa_id = {$siswa['id']} AND mapel_id = $mapel_id");
    $nilai = $check->fetch_assoc();
?>
    <tr>
        <td><?php echo $no++; ?></td>
        <td><?php echo $siswa['nis']; ?></td>
        <td>
            <?php echo $siswa['nama']; ?>
            <input type="hidden" name="siswa_id[]" value="<?php echo $siswa['id']; ?>">
        </td>
        <td>
            <input type="number" name="uh[]" class="form-control" value="<?php echo $nilai['uh'] ?? ''; ?>" step="0.01" min="0" max="100" required>
        </td>
        <td>
            <input type="number" name="uts[]" class="form-control" value="<?php echo $nilai['uts'] ?? ''; ?>" step="0.01" min="0" max="100" required>
        </td>
        <td>
            <input type="number" name="uas[]" class="form-control" value="<?php echo $nilai['uas'] ?? ''; ?>" step="0.01" min="0" max="100" required>
        </td>
        <td>
            <input type="number" name="tugas[]" class="form-control" value="<?php echo $nilai['tugas'] ?? ''; ?>" step="0.01" min="0" max="100" required>
        </td>
    </tr>
<?php
}
?>