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

    // Query untuk mendapatkan data karyawan berdasarkan ID
    $sql = "SELECT * FROM absensi WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Ambil data karyawan
        $data = $result->fetch_assoc();
    } else {
        // Jika data tidak ditemukan
        echo "Data tidak ditemukan!";
        exit;
    }
} else {
    // Jika tidak ada ID yang diterima, arahkan ke dashboard
    header("Location: dashboard.php");
    exit;
}

// Cek apakah form telah disubmit untuk update data
if (isset($_POST['update'])) {
    // Ambil data dari form
    $nama = $_POST['nama'];
    $status = $_POST['status'];
    $location = $_POST['location'];
    $tanggal_absen = $_POST['tanggal_absen'];
    $waktu_absen = $_POST['waktu_absen'];

    // Query untuk update data karyawan
    $sql = "UPDATE absensi SET nama = ?, status = ?, gps_location = ?, tanggal_absen = ?, waktu_absen = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssi", $nama, $status, $location, $tanggal_absen, $waktu_absen, $id);

    if ($stmt->execute()) {
        // Redirect ke dashboard setelah berhasil
        header("Location: dashboard.php");
        exit;
    } else {
        echo "Gagal memperbarui data.";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Data Karyawan</title>
    <link rel="stylesheet" href="./css/edit.css">
    <script src="./js/edit.js" defer></script>
</head>
<body>
    <div class="container">
        <h2>Edit Data Karyawan</h2>
        <form method="POST" action="">
            <label for="nama">Nama:</label>
            <input type="text" name="nama" id="nama" value="<?php echo htmlspecialchars($data['nama']); ?>" required>

            <label for="status">Status:</label>
            <input type="text" name="status" id="status" value="<?php echo htmlspecialchars($data['status']); ?>" required>

            <label for="location">Lokasi:</label>
            <input type="text" name="location" id="location" value="<?php echo htmlspecialchars($data['gps_location']); ?>" required>

            <label for="tanggal_absen">Tanggal Absensi:</label>
            <input type="date" name="tanggal_absen" id="tanggal_absen" value="<?php echo htmlspecialchars($data['tanggal_absen']); ?>" required>

            <label for="waktu_absen">Waktu Absensi:</label>
            <input type="time" name="waktu_absen" id="waktu_absen" value="<?php echo htmlspecialchars($data['waktu_absen']); ?>" required>

            <button type="submit" name="update">Update</button>
        </form>
        <a href="dashboard.php">Kembali ke Dashboard</a>
    </div>
</body>
</html>
