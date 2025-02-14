<?php
session_start();

// Pastikan hanya admin yang bisa mengakses
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit;
}

// Panggil file koneksi
require 'server.php';

// Cek apakah ada ID yang diterima dari URL
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Query untuk menghapus data berdasarkan ID
    $sql = "DELETE FROM absensi WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        // Redirect ke dashboard setelah berhasil
        header("Location: dashboard.php");
        exit;
    } else {
        echo "Gagal menghapus data.";
    }
} else {
    // Jika tidak ada ID yang diterima, arahkan ke dashboard
    header("Location: dashboard.php");
    exit;
}

$conn->close();
?>
