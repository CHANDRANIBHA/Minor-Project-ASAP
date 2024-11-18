// Toggle Sidebar
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


// Close notification dropdown when clicking outside
document.addEventListener('click', function(event) {
    const dropdown = document.getElementById('notificationDropdown');
    const notificationIcon = document.querySelector('.notification');
    if (!notificationIcon.contains(event.target) && dropdown.style.display === 'block') {
        dropdown.style.display = 'none';
    }
});

// Animate Number Counting
function animateCount(element, end, duration) {
    let startTimestamp = null;
    const step = (timestamp) => {
        if (!startTimestamp) startTimestamp = timestamp;
        const progress = Math.min((timestamp - startTimestamp) / duration, 1);
        element.textContent = Math.floor(progress * end);
        if (progress < 1) requestAnimationFrame(step);
    };
    requestAnimationFrame(step);
}

document.addEventListener("DOMContentLoaded", () => {
    animateCount(document.getElementById('studentCount'), parseInt(document.getElementById('studentCount').textContent), 2000);
    animateCount(document.getElementById('teacherCount'), parseInt(document.getElementById('teacherCount').textContent), 2000);
});

// Define the navigateTo function for navigation
function navigateTo(page) {
    // Simply change the window location to the desired page
    window.location.href = page;
}

// Function to logout
function logout() {
    // Optionally you can redirect to a logout script
    window.location.href = "logout.php";
}

// Toggle notification dropdown
function toggleNotificationDropdown() {
    var notificationDropdown = document.getElementById("notificationDropdown");
    notificationDropdown.style.display = notificationDropdown.style.display === "block" ? "none" : "block";
}
// Toggle Sidebar function
document.getElementById('menuToggle').addEventListener('click', function () {
    const sidebar = document.getElementById('sidebar');
    const menuIcon = document.getElementById('menuToggle');

    // Toggle the width of the sidebar
    sidebar.classList.toggle('collapsed');

    // Toggle the icon between "open" and "close"
    if (sidebar.classList.contains('collapsed')) {
        menuIcon.innerHTML = '<i class="fas fa-bars"></i>'; // Hamburger icon
    } else {
        menuIcon.innerHTML = '<i class="fas fa-times"></i>'; // X icon
    }
});
