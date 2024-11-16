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

// Semester change functionality
document.getElementById('semester-select').addEventListener('change', function () {
    const newSemester = this.value;

    const urlParams = new URLSearchParams(window.location.search);
    urlParams.set('semester', newSemester); 

    const newUrl = window.location.pathname + '?' + urlParams.toString();
    window.history.pushState({}, '', newUrl);

    document.getElementById('semester-display').textContent = `Semester: ${newSemester}`;
    console.log("Updated URL:", newUrl);
});

// Save button functionality for marks
document.getElementById('save-button').addEventListener('click', function() {
    // Collecting all the rows and extracting values
    let marksData = [];
    let rows = document.querySelectorAll("#marks-table tbody tr");
    
    rows.forEach(row => {
        let evaluation_id = row.getAttribute("data-evaluation-id");
        let quant_marks = row.querySelector(".marks-input[id*='quant']").value;
        let lr_marks = row.querySelector(".marks-input[id*='lr']").value;

        marksData.push({
            evaluation_id: evaluation_id,
            quant_marks: quant_marks,
            lr_marks: lr_marks
        });
    });

    // Send the collected data via AJAX
    fetch('hash2.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            student_id: "<?php echo $student['std_id']; ?>", // Passing the student id from PHP
            subject_id: "<?php echo $subject_id; ?>", // Subject ID passed in URL
            semester: "<?php echo $semester; ?>", // Semester value
            marksData: marksData // The collected marks data
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Marks saved successfully');
        } else {
            alert('Error saving marks');
        }
    })
    .catch(error => console.error('Error:', error));
});
