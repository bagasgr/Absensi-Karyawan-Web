// Function to get the current GPS location
function getLocation() {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(showPosition, showError);
    } else {
        alert("Geolocation tidak didukung oleh browser ini.");
    }
}

// Function to display the location in the text field
function showPosition(position) {
    const lat = position.coords.latitude;
    const lon = position.coords.longitude;
    // Menampilkan lokasi GPS ke input field
    document.getElementById('gps-location').value = `Latitude: ${lat}, Longitude: ${lon}`;
}

// Function to handle errors when getting the GPS location
function showError(error) {
    switch(error.code) {
        case error.PERMISSION_DENIED:
            alert("Pengguna menolak permintaan untuk mendapatkan lokasi.");
            break;
        case error.POSITION_UNAVAILABLE:
            alert("Lokasi tidak dapat diakses.");
            break;
        case error.TIMEOUT:
            alert("Permintaan lokasi timeout.");
            break;
        case error.UNKNOWN_ERROR:
            alert("Terjadi kesalahan yang tidak diketahui.");
            break;
    }
}
