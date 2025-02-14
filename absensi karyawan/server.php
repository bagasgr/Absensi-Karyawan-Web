<?php
$host = 'localhost'; // atau alamat server database Anda
$username = 'root';  // username MySQL
$password = '';      // password MySQL (kosongkan jika tidak ada)
$database = 'e_absensi'; // nama database

// Membuat koneksi
$conn = new mysqli($host, $username, $password, $database);

// Mengecek koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}
?>
