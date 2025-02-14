<?php
require 'server.php'; // Panggil file koneksi database

if (isset($_GET['id'])) {
    $id = intval($_GET['id']); // Pastikan ID adalah integer

    // Query untuk mengambil data gambar berdasarkan ID
    $sql = "SELECT foto FROM absensi WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->bind_result($foto);
    $stmt->fetch();

    // Kirimkan gambar sebagai respon
    if ($foto) {
        header("Content-Type: image/jpeg"); // Atur header tipe gambar
        echo $foto; // Tampilkan data gambar
    } else {
        echo "Gambar tidak ditemukan.";
    }

    $stmt->close();
} else {
    echo "ID tidak valid.";
}

$conn->close();
?>
