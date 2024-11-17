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
// Function to save marks to localStorage
function saveMarks(studentName, chapter, data) {
    let studentData = JSON.parse(localStorage.getItem(studentName)) || {};
    studentData[chapter] = data;
    localStorage.setItem(studentName, JSON.stringify(studentData));
}

// Function to collect marks data and save it
function saveMarksForStudent() {
    const studentName = document.querySelector('.panel h3').innerText;
    const chapter = document.querySelector('.chapter-dropdown').value;
    const data = {};

    document.querySelectorAll('.marks-table tbody tr').forEach(row => {
        const rowKey = row.querySelector('td').innerText;
        data[rowKey] = {
            internalMarks1: row.querySelector('input[data-col="1"]').value,
            internalMarks2: row.querySelector('input[data-col="2"]').value,
            assignment: row.querySelector('input[data-col="3"]').value,
            mainExam: row.querySelector('input[data-col="4"]').value,
            total: row.querySelector('.total').innerText
        };
    });

    saveMarks(studentName, chapter, data);
}
