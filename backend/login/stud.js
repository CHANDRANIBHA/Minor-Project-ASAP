// Menu Toggle
document.getElementById('menuToggle').addEventListener('click', function() {
    const sidebar = document.getElementById('sidebar');
    const isOpen = sidebar.style.width === '250px';
    sidebar.style.width = isOpen ? '0' : '250px';
    this.querySelector('i').classList.toggle('fa-bars', isOpen); // Change icon to bars if open
    this.querySelector('i').classList.toggle('fa-times', !isOpen); 
   
});

// Show/Hide Semester Dropdowns
document.querySelectorAll('.select-sem-btn').forEach(button => {
    button.addEventListener('click', function() {
        const dropdown = this.nextElementSibling;
        dropdown.style.display = dropdown.style.display === 'block' ? 'none' : 'block'; // Toggle dropdown visibility
        document.querySelectorAll('.semester-dropdown').forEach(drop => {
            if (drop !== dropdown) drop.style.display = 'none'; // Hide other dropdowns
        });
    });
});

// Show/Hide Training Options
document.querySelectorAll('.select-training-btn').forEach(button => {
    button.addEventListener('click', function() {
        const dropdown = document.getElementById('training-dropdown');
        dropdown.style.display = dropdown.style.display === 'block' ? 'none' : 'block'; // Toggle training dropdown visibility
    });
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

// Graph Placeholder (just a random message for demo purpose)
const graphCanvas = document.getElementById('randomGraph');
const ctx = graphCanvas.getContext('2d');
graphCanvas.width = 300; // Adjust the width
graphCanvas.height = 200; // Adjust the height
ctx.fillStyle = 'lightblue';
ctx.fillRect(0, 0, graphCanvas.width, graphCanvas.height);
ctx.fillStyle = 'black';
ctx.fillText('Random Graph Placeholder', 50, 100);

document.querySelectorAll('select criteria').forEach(button => {
    button.addEventListener('click', function() {
        const dropdown = this.nextElementSibling;
        dropdown.style.display = dropdown.style.display === 'block' ? 'none' : 'block'; // Toggle dropdown visibility
        document.querySelectorAll('criteria dropdown').forEach(drop => {
            if (drop !== dropdown) drop.style.display = 'none'; // Hide other dropdowns
        });
    });
});
// Function to toggle the dropdown visibility
function toggleDropdown() {
    const dropdown = document.getElementById('resources-dropdown');
    dropdown.style.display = dropdown.style.display === 'block' ? 'none' : 'block';
}
 
// Function to handle navigation
function navigateTo(page) {
    if (page === 'home') {
        window.location.href = 'teacher html.html'; // Redirect to Teacher Page
    } else if (page === 'aptitude') {
        window.location.href = 'aptitude html.html'; // Redirect to Aptitude Resources
    } else if (page === 'verbal') {
        window.location.href = 'verbal html.html'; // Redirect to Verbal Resources
    } else if (page === 'softskills') {
        window.location.href = 'softskills html.html'; // Redirect to Soft Skills Resources
    } else if (page === 'training') {
        window.location.href = 'training html.html'; // Redirect to Personal Training Resources
    }
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
 