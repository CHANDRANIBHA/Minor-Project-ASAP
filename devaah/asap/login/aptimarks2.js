// Toggle the sidebar menu
document.getElementById('menuToggle').addEventListener('click', function () {
    const sidebar = document.getElementById('sidebar');
    if (sidebar.style.width === '250px') {
        sidebar.style.width = '0';
    } else {
        sidebar.style.width = '250px';
    }
    this.querySelector('i').classList.toggle('fa-bars', sidebar.style.width !== '250px');
    this.querySelector('i').classList.toggle('fa-times', sidebar.style.width === '250px');
});

// Variables
const studentName = "<?php echo htmlspecialchars($student_name); ?>";
const mode = "<?php echo htmlspecialchars($mode); ?>";

const saveButton = document.getElementById('save-button');
const marksInputs = document.querySelectorAll('.marks-input');

// Add event listener to save button if in update mode
if (mode === 'update') {
    saveButton.addEventListener('click', () => {
        // Save marks data
        alert('Marks saved!');
    });
}

// Function to calculate totals
function calculateTotals() {
    const evaluations = ['midterm', 'endsem', 'assign1', 'assign2', 'extra1', 'extra2'];
    let quantGrandTotal = 0;
    let lrGrandTotal = 0;
    evaluations.forEach(eval => {
        const quantScore = parseFloat(document.getElementById(`${eval}-quant`).value) || 0;
        const lrScore = parseFloat(document.getElementById(`${eval}-lr`).value) || 0;
        const totalScore = quantScore + lrScore;
        document.getElementById(`${eval}-total`).innerText = totalScore;
        quantGrandTotal += quantScore;
        lrGrandTotal += lrScore;
    });
    const grandTotal = quantGrandTotal + lrGrandTotal;
    document.getElementById('quant-grand-total').innerText = quantGrandTotal;
    document.getElementById('lr-grand-total').innerText = lrGrandTotal;
    document.getElementById('grand-total').innerText = grandTotal;
}

// Initial calculation and event listeners for inputs
calculateTotals();
marksInputs.forEach(input => {
    input.addEventListener('input', calculateTotals);
});

document.getElementById('semester-select').addEventListener('change', function () {
    // Get the new semester value from the dropdown
    const newSemester = this.value;

    // Get the current URL and update the semester query parameter
    const urlParams = new URLSearchParams(window.location.search);
    urlParams.set('semester', newSemester); // Set the new semester value in the URL

    // Update the URL in the address bar without reloading the page
    const newUrl = window.location.pathname + '?' + urlParams.toString();
    window.history.pushState({}, '', newUrl);  // Change the URL

    // Update the displayed semester in the page
    document.getElementById('semester-display').textContent = `Semester: ${newSemester}`;
    
    // Optionally, log for debugging
    console.log("Updated URL:", newUrl);
});
