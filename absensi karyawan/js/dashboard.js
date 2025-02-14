document.addEventListener('DOMContentLoaded', function() {
    // Add event listener to all employee rows
    let employeeRows = document.querySelectorAll('.employee-row');
    employeeRows.forEach(function(row) {
        row.addEventListener('click', function() {
            // Get employee data from the clicked row's data attributes
            let nama = row.getAttribute('data-nama');
            let status = row.getAttribute('data-status');
            let location = row.getAttribute('data-location');
            let imageUrl = row.getAttribute('data-image');

            // Display the data in the modal
            showEmployeeDetails(nama, status, location, imageUrl);
        });
    });

    // Function to show employee details in the modal
    function showEmployeeDetails(nama, status, location, imageUrl) {
        // Get modal and modal elements
        let modal = document.getElementById('employeeModal');
        let modalNama = modal.querySelector('#modalNama');
        let modalStatus = modal.querySelector('#modalStatus');
        let modalLocation = modal.querySelector('#modalLocation');
        let modalImage = modal.querySelector('#modalImage');

        // Fill modal with employee data
        modalNama.textContent = "Nama: " + nama;
        modalStatus.textContent = "Status: " + status;
        modalLocation.textContent = "Lokasi: " + location;
        modalImage.src = imageUrl;

        // Show the modal
        modal.style.display = 'block';
    }

    // Close the modal when clicking the close button or outside the modal
    let closeBtn = document.querySelector('.close');
    closeBtn.addEventListener('click', function() {
        document.getElementById('employeeModal').style.display = 'none';
    });

    // Close the modal if clicked outside of it
    window.onclick = function(event) {
        let modal = document.getElementById('employeeModal');
        if (event.target == modal) {
            modal.style.display = 'none';
        }
    }
});
