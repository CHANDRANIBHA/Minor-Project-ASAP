// Menu Toggle
document.getElementById('menuToggle').addEventListener('click', function() {
  const sidebar = document.getElementById('sidebar');
  const isOpen = sidebar.style.width === '250px';
  sidebar.style.width = isOpen ? '0' : '250px';
  this.querySelector('i').classList.toggle('fa-bars', isOpen); // Change icon to bars if open
  this.querySelector('i').classList.toggle('fa-times', !isOpen);
});

// Notification Dropdown
document.getElementById('notification').addEventListener('click', function() {
  const dropdown = document.getElementById('notificationDropdown');
  dropdown.style.display = dropdown.style.display === 'block' ? 'none' : 'block'; // Toggle notification dropdown visibility
});

// Close notification dropdown when clicking outside
document.addEventListener('click', function(event) {
  const dropdown = document.getElementById('notificationDropdown');
  if (!document.getElementById('notification').contains(event.target) && dropdown.style.display === 'block') {
      dropdown.style.display = 'none';
  }
});

// Handle menu toggle on DOMContentLoaded
document.addEventListener('DOMContentLoaded', function() {
  const menuIcon = document.getElementById('menuToggle');
  const sidebar = document.getElementById('sidebar');
  const closeSidebar = document.getElementById('closeSidebar');

  // Toggle sidebar visibility
  menuIcon.addEventListener('click', function() {
      sidebar.style.display = 'block';
  });

  // Close sidebar
  if (closeSidebar) {
      closeSidebar.addEventListener('click', function() {
          sidebar.style.display = 'none';
      });
  }
});

// Function to toggle the dropdown visibility
function toggleDropdown() {
  const dropdown = document.getElementById('resources-dropdown');
  dropdown.style.display = dropdown.style.display === 'block' ? 'none' : 'block';
}

function navigateTo(page) {
  // Log to confirm the function is triggered
  console.log("Navigating to:", page);
  window.location.href = page;
}

// Close dropdown when clicking outside
window.onclick = function(event) {
  if (!event.target.matches('.dropdown span')) {
      const dropdowns = document.getElementsByClassName("dropdown-content");
      for (let i = 0; i < dropdowns.length; i++) {
          const openDropdown = dropdowns[i];
          if (openDropdown.style.display === 'block') {
              openDropdown.style.display = 'none';
          }
      }
  }
}

// Initialize after DOM content loaded
document.addEventListener('DOMContentLoaded', () => {
  const teacherActions = document.getElementById('teacher-actions');
  const uploadForm = document.getElementById('upload-form');
  const fileList = document.getElementById('file-list');
  const searchBar = document.getElementById('search-bar');

  // Handle file upload form submission
  uploadForm.addEventListener('submit', function (e) {
      e.preventDefault();

      const fileTitleInput = this.querySelector('input[name="file-title"]');
      const fileInput = this.querySelector('input[name="file-upload"]');
      const allowedTitles = ['aptitude', 'verbal', 'softskills', 'professional training'];

      let hasError = false;

      // Validate file title
      if (!allowedTitles.includes(fileTitleInput.value.trim().toLowerCase())) {
          alert('Invalid file title. Allowed titles: aptitude, verbal, softskills, professional training.');
          hasError = true;
      }

      // Validate file upload
      if (!fileInput.files.length) {
          alert('Please choose a file to upload.');
          hasError = true;
      }

      // If validation fails, stop further execution
      if (hasError) return;

      // Proceed with the form submission
      this.submit();
  });

  // Handle search bar input
  searchBar.addEventListener('input', function () {
      const query = this.value.trim().toLowerCase();

      fetch('reso.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `query=${encodeURIComponent(query)}`
    })
    .then(response => {
        console.log('Response received:', response);
        return response.json();
    })
    .then(files => {
        // Process files
    })
    .catch(error => console.error('Error fetching files:', error));


  // File delete handler
  fileList.addEventListener('click', function (event) {
      if (event.target.classList.contains('btn-delete')) {
          const fileCard = event.target.closest('.file-card');
          const resourceId = fileCard.querySelector('input[name="resource-id"]').value;
          const fileName = fileCard.querySelector('input[name="resource-file"]').value;

          if (confirm('Are you sure you want to delete this file?')) {
              fetch('reso.php', {
                  method: 'POST',
                  headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                  body: `delete-resource=true&resource-id=${encodeURIComponent(resourceId)}&resource-file=${encodeURIComponent(fileName)}`
              })
                  .then(response => response.text())
                  .then(() => {
                      fileCard.remove();
                      alert('File deleted successfully.');
                  })
                  .catch(error => console.error('Error deleting file:', error));
          }
      }
  });
})

  // Navigation helper
  const navigateTo = (page) => {
      window.location.href = page;
  };

  // Navigation menu
  document.querySelectorAll('#menu li').forEach(menuItem => {
      menuItem.addEventListener('click', function () {
          const targetPage = this.getAttribute('onclick').match(/navigateTo\('(.*?)'\)/)[1];
          navigateTo(targetPage);
      });
  });
});