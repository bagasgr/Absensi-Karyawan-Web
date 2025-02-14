<?php
session_start();

// Pastikan user sudah login sebagai admin
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit;
}

// Konfigurasi database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "e_absensi";

// Membuat koneksi ke database
$conn = new mysqli($servername, $username, $password, $dbname);

// Mengecek koneksi
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Proses saat form dikirim
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Ambil data dari form
    $nama = $_POST['nama'];
    $status = $_POST['status'];
    $gps_location = $_POST['gps_location'];
    $tanggal_absensi = $_POST['tanggal_absensi']; // Tanggal absensi yang dimasukkan
    $waktu_absensi = $_POST['waktu_absensi']; // Waktu absensi yang dimasukkan

    // Proses upload foto
    $foto = $_FILES['foto']['name'];
    $foto_tmp = $_FILES['foto']['tmp_name'];
    $upload_folder = "uploads/";
    $foto_path = $upload_folder . basename($foto);

    // Pastikan folder uploads ada
    if (!is_dir($upload_folder)) {
        mkdir($upload_folder, 0777, true);
    }

    // Pindahkan file dari folder sementara ke folder tujuan
    if (move_uploaded_file($foto_tmp, $foto_path)) {
        // Convert foto menjadi format BLOB untuk disimpan ke database
        $foto_blob = file_get_contents($foto_path);

        // Siapkan query untuk menyimpan data absensi ke tabel
        $stmt = $conn->prepare("
            INSERT INTO absensi (nama, status, foto, gps_location, tanggal_absen, waktu_absen) 
            VALUES (?, ?, ?, ?, ?, ?)
        ");
        $stmt->bind_param("ssssss", $nama, $status, $foto_blob, $gps_location, $tanggal_absensi, $waktu_absensi);

        // Eksekusi query
        if ($stmt->execute()) {
            echo "<script>alert('Absensi berhasil dicatat!');</script>";
        } else {
            echo "Error: " . $stmt->error;
        }

        // Hapus file fisik setelah diupload
        unlink($foto_path);
    } else {
        echo "<script>alert('Gagal mengunggah foto. Pastikan folder uploads memiliki izin tulis.');</script>";
    }
}

$conn->close();
?>



<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Input Absensi Karyawan</title>
    <link rel="stylesheet" href="./css/input.css">
    <script>
        // JavaScript untuk memberikan efek animasi pada form
        window.onload = function() {
            document.getElementById("nama").focus();
        }
    </script>
</head>
<body>
    <h2>Input Absensi Karyawan</h2>
    <form action="input.php" method="POST" enctype="multipart/form-data">
        <label for="nama">Nama Karyawan:</label>
        <input type="text" id="nama" name="nama" required><br><br>

        <label for="status">Status Absensi:</label>
        <select id="status" name="status" required>
            <option value="Hadir">Hadir</option>
            <option value="Sakit">Sakit</option>
            <option value="Izin">Izin</option>
        </select><br><br>

        <label for="foto">Foto Selfie:</label>
        <input type="file" id="foto" name="foto" accept="image/*" required><br><br>

        <label for="gps_location">Lokasi GPS (Latitude, Longitude):</label>
        <input type="text" id="gps_location" name="gps_location" required><br><br>

        <!-- Input untuk Tanggal dan Waktu Absensi -->
        <label for="tanggal_absensi">Tanggal Absensi:</label>
        <input type="date" id="tanggal_absensi" name="tanggal_absensi" required><br><br>

        <label for="waktu_absensi">Waktu Absensi:</label>
        <input type="time" id="waktu_absensi" name="waktu_absensi" required><br><br>

        <input type="submit" value="Input Absensi">
    </form>

    <br>
    <a href="dashboard.php">Kembali ke Dashboard</a>
</body>
</html>
