// Toggle Sidebar
document.getElementById('menuToggle').addEventListener('click', function() {
    const sidebar = document.getElementById('sidebar');
    sidebar.classList.toggle('sidebar-hidden');
    this.querySelector('i').classList.toggle('fa-bars');
    this.querySelector('i').classList.toggle('fa-times');
});

// Notification Dropdown Toggle
function toggleNotificationDropdown() {
    const dropdown = document.getElementById('notificationDropdown');
    dropdown.style.display = dropdown.style.display === 'block' ? 'none' : 'block';
}

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
