<?php
session_start();

// Pastikan hanya admin yang bisa mengakses
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit;
}

// Panggil file koneksi
require 'server.php';

// Initialize the search term
$searchTerm = '';

// Check if the form was submitted
if (isset($_POST['search'])) {
    // Get the search term from the input
    $searchTerm = $_POST['searchTerm'];
    // Modify the query to search based on the input term
    $sql = "SELECT * FROM absensi WHERE nama LIKE ? OR status LIKE ? OR gps_location LIKE ?";
    $stmt = $conn->prepare($sql);
    $searchTermLike = "%" . $searchTerm . "%";
    $stmt->bind_param("sss", $searchTermLike, $searchTermLike, $searchTermLike);
    $stmt->execute();
    $result = $stmt->get_result();
} elseif (isset($_POST['showAll'])) {
    // If "Show All" button is pressed, reset the query to get all records
    $sql = "SELECT * FROM absensi";
    $result = $conn->query($sql);
} else {
    // Default query to fetch all records
    $sql = "SELECT * FROM absensi";
    $result = $conn->query($sql);
}

// Mengecek apakah ada data
if ($result->num_rows > 0) {
    // Mengambil data sebagai array asosiatif
    $absensiData = $result->fetch_all(MYSQLI_ASSOC);
} else {
    $absensiData = []; // Jika tidak ada data
}

// Menutup koneksi
$conn->close();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin E-Absensi</title>
    <link rel="stylesheet" href="./css/dashbboard.css"> <!-- Link ke file CSS -->
    <script src="./js/dashboard.js" defer></script> <!-- Link ke file JS -->
    <style>
        /* Styles for the modal */
        .modal {
            display: none; 
            position: fixed; 
            z-index: 1; 
            left: 0; top: 0; 
            width: 100%; 
            height: 100%; 
            overflow: auto; 
            background-color: rgb(0,0,0); 
            background-color: rgba(0,0,0,0.4); 
            padding-top: 60px;
        }
        .modal-content {
            background-color: #fefefe;
            margin: 5% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
        }
        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }
        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }
        .modal-buttons {
            margin-top: 20px;
        }
        .modal-buttons button {
            margin-right: 10px;
        }

        /* Styles for logout button */
        .logout-btn {
            background-color: #f44336; /* Red background */
            color: white;
            padding: 10px 20px;
            border: none;
            cursor: pointer;
            font-size: 16px;
            margin: 10px;
            border-radius: 5px;
        }

        .logout-btn:hover {
            background-color: #d32f2f; /* Darker red when hovered */
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Dashboard Admin E-Absensi</h2>
        <button class="logout-btn" onclick="window.location.href='logout.php';">Logout</button>

        <h3>Rekap Absensi Karyawan</h3>
        <a href="input.php">Input Absensi Karyawan</a>

        <!-- Search Form -->
        <form method="POST" action="">
            <input type="text" name="searchTerm" placeholder="Cari berdasarkan nama, status, atau lokasi" value="<?php echo htmlspecialchars($searchTerm); ?>" />
            <button type="submit" name="search">Cari</button>
        </form>

        <!-- Button to show all data -->
        <form method="POST" action="">
            <button type="submit" name="showAll">Tampilkan Semua Data Karyawan</button>
        </form>

        <table>
            <thead>
                <tr>
                    <th>Nama</th>
                    <th>Status</th>
                    <th>Foto Selfie</th>
                    <th>Lokasi</th>
                    <th>Tanggal Absensi</th>
                    <th>Tanggal dan Waktu</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($absensiData as $data): ?>
                    <tr class="employee-row" data-id="<?php echo htmlspecialchars($data['id']); ?>" 
                        data-nama="<?php echo htmlspecialchars($data['nama']); ?>" 
                        data-status="<?php echo htmlspecialchars($data['status']); ?>" 
                        data-location="<?php echo htmlspecialchars($data['gps_location']); ?>" 
                        data-tanggal_absen="<?php echo date('d-m-Y', strtotime($data['tanggal_absen'])); ?>"
                        data-waktu_absen="<?php echo htmlspecialchars($data['waktu_absen']); ?>" 
                        data-image="get_image.php?id=<?php echo htmlspecialchars($data['id']); ?>">
                        <td><?php echo htmlspecialchars($data['nama']); ?></td>
                        <td><?php echo htmlspecialchars($data['status']); ?></td>
                        <td>
                            <img src="get_image.php?id=<?php echo htmlspecialchars($data['id']); ?>" alt="Selfie" style="width: 100px; height: auto;">
                        </td>
                        <td><?php echo htmlspecialchars($data['gps_location']); ?></td>
                        <td><?php echo date('d-m-Y', strtotime($data['tanggal_absen'])); ?></td>
                        <td><?php echo htmlspecialchars($data['waktu_absen']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- Modal for showing employee details -->
    <div id="employeeModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h3>Detail Karyawan</h3>
            <p id="modalNama"></p>
            <p id="modalStatus"></p>
            <p id="modalLocation"></p>
            <p id="modalTanggalAbsensi"></p>
            <p id="modalWaktuAbsensi"></p>
            <img id="modalImage" src="" alt="Selfie" style="width: 150px; height: 150px;">
            
            <!-- Buttons for Edit and Delete -->
            <div class="modal-buttons">
                <button id="editButton">Edit</button>
                <button id="deleteButton">Hapus</button>
            </div>
        </div>
    </div>

    <script>
        // JavaScript to handle the modal functionality
        document.querySelectorAll('.employee-row').forEach(row => {
            row.addEventListener('click', function() {
                // Get the data from the clicked row
                var nama = this.getAttribute('data-nama');
                var status = this.getAttribute('data-status');
                var location = this.getAttribute('data-location');
                var tanggalAbsensi = this.getAttribute('data-tanggal_absen');
                var waktuAbsensi = this.getAttribute('data-waktu_absen');
                var imageSrc = this.getAttribute('data-image');
                var id = this.getAttribute('data-id');
                
                // Set the modal content
                document.getElementById('modalNama').textContent = 'Nama: ' + nama;
                document.getElementById('modalStatus').textContent = 'Status: ' + status;
                document.getElementById('modalLocation').textContent = 'Lokasi: ' + location;
                document.getElementById('modalTanggalAbsensi').textContent = 'Tanggal Absensi: ' + tanggalAbsensi;
                document.getElementById('modalWaktuAbsensi').textContent = 'Waktu Absensi: ' + waktuAbsensi;
                document.getElementById('modalImage').src = imageSrc;

                // Show the modal
                document.getElementById('employeeModal').style.display = 'block';

                // Handle edit button click
                document.getElementById('editButton').addEventListener('click', function() {
                    window.location.href = 'edit.php?id=' + id; // Redirect to the edit page
                });

                // Handle delete button click
                document.getElementById('deleteButton').addEventListener('click', function() {
                    if (confirm("Apakah Anda yakin ingin menghapus data ini?")) {
                        window.location.href = 'delete.php?id=' + id; // Redirect to the delete script
                    }
                });
            });
        });

        // Close the modal when the user clicks on the 'x'
        document.querySelector('.close').addEventListener('click', function() {
            document.getElementById('employeeModal').style.display = 'none';
        });
    </script>
</body>
</html>
