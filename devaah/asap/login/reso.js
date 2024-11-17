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

document.addEventListener('DOMContentLoaded', () => {
    const role = 'teacher'; // Change this to 'student' or 'teacher'
    const teacherActions = document.getElementById('teacher-actions');
    const uploadForm = document.getElementById('upload-form');
    const fileList = document.getElementById('file-list');
    const searchBar = document.getElementById('search-bar');
    const fileViewModal = document.getElementById('file-view-modal');
    const fileContent = document.getElementById('file-content');
    const closeModal = document.getElementById('close-modal');
  
    document.getElementById('upload-form').addEventListener('submit', function(e) {
      e.preventDefault();
  
      const fileTitle = document.querySelector('input[name="file-title"]').value.trim().toLowerCase();
      const allowedTitles = ['aptitude', 'verbal', 'softskills', 'professional training'];
  
      if (!allowedTitles.includes(fileTitle)) {
          alert('Invalid file title. Allowed titles: aptitude, verbal, softskills, professional training.');
          return;  // Prevent form submission
      }
  
      // Proceed with the form submission if valid
      this.submit();
  });
  });
    // Show upload panel if the role is 'teacher'
    if (role === 'teacher') {
      teacherActions.classList.remove('hidden');
    }
  
    // Function to display an error message
    const showError = (inputElement, message) => {
      const errorDiv = document.createElement('div');
      errorDiv.classList.add('error-message');
      errorDiv.textContent = message;
  
      // Remove any existing error for the input before adding a new one
      const existingError = inputElement.parentElement.querySelector('.error-message');
      if (existingError) existingError.remove();
  
      inputElement.parentElement.appendChild(errorDiv);
  
      // Highlight the input with red border
      inputElement.classList.add('error-border');
    };
  
    // Function to clear error messages
    const clearError = (inputElement) => {
      const existingError = inputElement.parentElement.querySelector('.error-message');
      if (existingError) existingError.remove();
  
      // Remove red border from input
      inputElement.classList.remove('error-border');
    };
  
    uploadForm.addEventListener('submit', (e) => {
      e.preventDefault();
  
      const fileInput = document.getElementById('file-upload');
      const titleInput = document.getElementById('file-title');
      const file = fileInput.files[0];
      const title = titleInput.value.trim().toLowerCase();
  
      let hasError = false;
  
      // Validation: File Title
      if (!allowedTitles.includes(title)) {
        showError(titleInput, 'Invalid file title. Allowed titles: aptitude, verbal, softskills, professional training.');
        hasError = true;
      } else {
        clearError(titleInput);
      }
  
      // Validation: File Upload
      if (!file) {
        showError(fileInput, 'Please choose a file to upload.');
        hasError = true;
      } else {
        clearError(fileInput);
      }
  
      // If any validation fails, stop further execution
      if (hasError) return;
  
      // Create a file card for valid input
      const fileCard = document.createElement('div');
      fileCard.classList.add('file-card');
  
      const fileName = file.name;
      const fileURL = URL.createObjectURL(file);
  
      fileCard.innerHTML = `
      <h3>${title}</h3>
      <p>${fileName}</p>
      <div class="icons">
        <i class="download" title="Download">&#128190;</i>
        <i class="view" title="View">&#128065;</i>
        ${role === 'teacher' ? '<i class="delete" title="Delete">&#128465;</i>' : ''}
      </div>
    `;    
  
      // Event listeners for icons
      fileCard.querySelector('.download').addEventListener('click', () => {
        const a = document.createElement('a');
        a.href = fileURL;
        a.download = fileName;
        a.click();
      });
  
      fileCard.querySelector('.view').addEventListener('click', () => {
        fileContent.innerHTML = <iframe src="${fileURL}" width="100%" height="400px"></iframe>;
        fileViewModal.classList.remove('hidden');
      });
  
      if (role === 'teacher') {
        fileCard.querySelector('.delete').addEventListener('click', () => fileCard.remove());
      }
  
      fileList.appendChild(fileCard);
      fileInput.value = '';
      titleInput.value = '';
    });

    document.addEventListener('DOMContentLoaded', () => {
      const searchBar = document.getElementById('search-bar');
      const fileList = document.getElementById('file-list');

      const fetchFiles = (query = '') => {
        fetch('reso.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: query=${encodeURIComponent(query)},
        })
        .then(response => response.json())
        .then(files => {
            fileList.innerHTML = '';
            files.forEach(file => {
                const fileCard = document.createElement('div');
                fileCard.classList.add('file-card');
                fileCard.innerHTML = `
                    <h3>${file.resource_title}</h3>
                    <p>${file.resource_file}</p>
                    <a href="uploads/${file.resource_file}" download>Download</a>
                `;
                fileList.appendChild(fileCard);
            });
        })
        .catch(error => console.error('Error fetching files:', error));
    };
  })
  // script.js - Dashboard navigation logic
document.getElementById('chat').addEventListener('click', function() {
    window.location.href = 'chattr.html'; // Navigates to chat page
});

function navigateTo(page) {
    window.location.href = page; // This will redirect to the specified page
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

document.getElementById('close-modal').addEventListener('click', () => {
    fileViewModal.classList.add('hidden');
  });
  
const content = role === 'teacher' 
  ? '<i class="fas fa-trash-alt delete" title="Delete"></i>' 
  : ''; // this should return an empty string when role is not 'teacher'

// Get the session menu item by its ID
const sessionMenu = document.getElementById('session');

// Add an event listener for the click event
sessionMenu.addEventListener('click', function() {
    // Navigate to the session page
    window.location.href = 'sessionform.html'; // Adjust this path if your session page is in a different folder
});

fileCard.querySelector('.delete').addEventListener('click', (event) => {
    const cardToRemove = event.target.closest('.file-card');
    if (cardToRemove) {
        cardToRemove.remove();
    }
});

function confirmDelete() {
  return confirm('Are you sure you want to delete this file?');
}
