<?php
// Hapus semua output buffer
ob_clean();

$host = "localhost";
$user = "root";
$pass = "";

$conn = new mysqli($host, $user, $pass);

if ($conn->connect_error) {
    die("Koneksi ke MySQL gagal: " . $conn->connect_error);
}

// Hapus database jika sudah ada
$conn->query("DROP DATABASE IF EXISTS dashboard_mtsn_ngada");

// Buat database baru
$sql = "CREATE DATABASE dashboard_mtsn_ngada";
if ($conn->query($sql) === TRUE) {
    echo "✅ Database 'dashboard_mtsn_ngada' berhasil dibuat!<br>";
} else {
    die("❌ Gagal membuat database: " . $conn->error);
}

$conn->select_db("dashboard_mtsn_ngada");

// Buat tabel-tabel
$tables = [
    "CREATE TABLE kelas (
        id INT PRIMARY KEY AUTO_INCREMENT,
        nama_kelas VARCHAR(50) NOT NULL,
        wali_kelas VARCHAR(100),
        tingkat VARCHAR(20),
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )",

    "CREATE TABLE mapel (
        id INT PRIMARY KEY AUTO_INCREMENT,
        kode_mapel VARCHAR(20) UNIQUE NOT NULL,
        nama_mapel VARCHAR(100) NOT NULL,
        kkm INT DEFAULT 75,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )",

    "CREATE TABLE guru (
        id INT PRIMARY KEY AUTO_INCREMENT,
        nip VARCHAR(50) UNIQUE NOT NULL,
        nama VARCHAR(100) NOT NULL,
        mapel_id INT,
        jenis_kelamin ENUM('L','P'),
        no_telp VARCHAR(15),
        alamat TEXT,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (mapel_id) REFERENCES mapel(id) ON DELETE SET NULL
    )",

    "CREATE TABLE siswa (
        id INT PRIMARY KEY AUTO_INCREMENT,
        nis VARCHAR(50) UNIQUE NOT NULL,
        nisn VARCHAR(50) UNIQUE,
        nama VARCHAR(100) NOT NULL,
        kelas_id INT,
        jenis_kelamin ENUM('L','P'),
        tempat_lahir VARCHAR(50),
        tanggal_lahir DATE,
        alamat TEXT,
        nama_ayah VARCHAR(100),
        nama_ibu VARCHAR(100),
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (kelas_id) REFERENCES kelas(id) ON DELETE SET NULL
    )",

    "CREATE TABLE jadwal (
        id INT PRIMARY KEY AUTO_INCREMENT,
        hari ENUM('Senin','Selasa','Rabu','Kamis','Jumat','Sabtu') NOT NULL,
        jam_mulai TIME NOT NULL,
        jam_selesai TIME NOT NULL,
        mapel_id INT,
        kelas_id INT,
        guru_id INT,
        ruangan VARCHAR(50),
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (mapel_id) REFERENCES mapel(id) ON DELETE CASCADE,
        FOREIGN KEY (kelas_id) REFERENCES kelas(id) ON DELETE CASCADE,
        FOREIGN KEY (guru_id) REFERENCES guru(id) ON DELETE CASCADE
    )",

    "CREATE TABLE nilai (
        id INT PRIMARY KEY AUTO_INCREMENT,
        siswa_id INT,
        mapel_id INT,
        uh DECIMAL(5,2),
        uts DECIMAL(5,2),
        uas DECIMAL(5,2),
        tugas DECIMAL(5,2),
        semester VARCHAR(20),
        tahun_ajaran VARCHAR(20),
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (siswa_id) REFERENCES siswa(id) ON DELETE CASCADE,
        FOREIGN KEY (mapel_id) REFERENCES mapel(id) ON DELETE CASCADE
    )",

    "CREATE TABLE users (
        id INT PRIMARY KEY AUTO_INCREMENT,
        username VARCHAR(50) UNIQUE NOT NULL,
        password VARCHAR(255) NOT NULL,
        role ENUM('admin','guru','walikelas') DEFAULT 'guru',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )"
];

foreach ($tables as $sql) {
    if ($conn->query($sql) === TRUE) {
        echo "✅ Tabel berhasil dibuat<br>";
    } else {
        echo "❌ Gagal membuat tabel: " . $conn->error . "<br>";
    }
}

// Insert data sample
$samples = [
    "INSERT INTO kelas (nama_kelas, wali_kelas, tingkat) VALUES
    ('7A', 'Dr. Ahmad', 'VII'),
    ('7B', 'Siti Aminah', 'VII'),
    ('8A', 'Budi Santoso', 'VIII'),
    ('8B', 'Dewi Lestari', 'VIII')",

    "INSERT INTO mapel (kode_mapel, nama_mapel, kkm) VALUES
    ('MTK', 'Matematika', 70),
    ('BIN', 'Bahasa Indonesia', 75),
    ('BING', 'Bahasa Inggris', 75),
    ('IPA', 'Ilmu Pengetahuan Alam', 70),
    ('IPS', 'Ilmu Pengetahuan Sosial', 70)",

    "INSERT INTO guru (nip, nama, mapel_id, jenis_kelamin) VALUES
    ('198001012010011001', 'Dr. Ahmad', 1, 'L'),
    ('198002012010012002', 'Siti Aminah', 2, 'P'),
    ('198003012010013003', 'Budi Santoso', 3, 'L'),
    ('198004012010014004', 'Dewi Lestari', 4, 'P')",

    "INSERT INTO siswa (nis, nisn, nama, kelas_id, jenis_kelamin) VALUES
    ('2024001', '0012345678', 'Budi Santoso', 1, 'L'),
    ('2024002', '0012345679', 'Ani Wijaya', 1, 'P'),
    ('2024003', '0012345680', 'Citra Dewi', 2, 'P'),
    ('2024004', '0012345681', 'Dodi Setiawan', 2, 'L')",

    "INSERT INTO users (username, password, role) VALUES
    ('admin', MD5('admin123'), 'admin'),
    ('guru', MD5('guru123'), 'guru')"
];

foreach ($samples as $sql) {
    if ($conn->query($sql) === TRUE) {
        echo "✅ Data sample berhasil dimasukkan<br>";
    } else {
        echo "❌ Gagal memasukkan data: " . $conn->error . "<br>";
    }
}

echo "<hr>";
echo "<h2 style='color: green;'>✅ INSTALASI SELESAI!</h2>";
echo "<p>Silakan <a href='index.php'>klik di sini</a> untuk membuka aplikasi</p>";
echo "<p>Login: <strong>admin / admin123</strong></p>";

$conn->close();
