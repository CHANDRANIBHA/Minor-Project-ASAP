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
  
    const allowedTitles = ['aptitude', 'verbal', 'softskills', 'professional training'];
  
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
        fileContent.innerHTML = `<iframe src="${fileURL}" width="100%" height="400px"></iframe>`;
        fileViewModal.classList.remove('hidden');
      });
  
      if (role === 'teacher') {
        fileCard.querySelector('.delete').addEventListener('click', () => fileCard.remove());
      }
  
      fileList.appendChild(fileCard);
      fileInput.value = '';
      titleInput.value = '';
    });
  
    searchBar.addEventListener('input', () => {
      const query = searchBar.value.toLowerCase();
      const files = document.querySelectorAll('.file-card');
      files.forEach((file) => {
        const title = file.querySelector('h3').textContent.toLowerCase();
        const name = file.querySelector('p').textContent.toLowerCase();
        file.style.display = title.includes(query) || name.includes(query) ? 'block' : 'none';
      });
    });
  
    closeModal.addEventListener('click', () => {
      fileViewModal.classList.add('hidden');
    });
  });
  
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