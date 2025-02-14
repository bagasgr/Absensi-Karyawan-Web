<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Menangani data absensi
    $status = $_POST['status'];
    $selfie = $_FILES['selfie']['name'];
    $lokasi = $_POST['lokasi'];

    // Upload foto selfie
    move_uploaded_file($_FILES['selfie']['tmp_name'], "uploads/$selfie");

    // Simpan ke database atau file (misalnya)
    // Simpan data absensi ke database di sini

    $message = "Absensi berhasil!";
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Absensi Karyawan</title>
    <link rel="stylesheet" href="styles.css">
    <script src="scripts.js" defer></script>
</head>
<body>
    <h2>Absensi Karyawan</h2>
    <form method="POST" enctype="multipart/form-data">
        <label for="status">Status Absensi:</label>
        <select name="status" id="status" required>
            <option value="Hadir">Hadir</option>
            <option value="Sakit">Sakit</option>
            <option value="Izin">Izin</option>
        </select><br>

        <label for="selfie">Foto Selfie:</label>
        <input type="file" name="selfie" required><br>

        <label for="lokasi">Lokasi (GPS):</label>
        <input type="text" name="lokasi" id="lokasi" required><br>

        <button type="submit">Absensi</button>
    </form>

    <?php if (isset($message)) { echo "<p>$message</p>"; } ?>
    
    <script>
        // Mendapatkan lokasi GPS (mungkin menggunakan API Geolocation)
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function(position) {
                document.getElementById('lokasi').value = position.coords.latitude + ', ' + position.coords.longitude;
            });
        } else {
            alert("Geolocation tidak didukung oleh browser ini.");
        }
    </script>
</body>
</html>
