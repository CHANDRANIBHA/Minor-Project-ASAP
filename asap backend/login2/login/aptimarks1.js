// Toggle the sidebar menu
document.getElementById('menuToggle').addEventListener('click', function () {
    const sidebar = document.getElementById('sidebar');
    const isOpen = sidebar.style.width === '250px';
    sidebar.style.width = isOpen ? '0' : '250px';
    this.querySelector('i').classList.toggle('fa-bars', isOpen);
    this.querySelector('i').classList.toggle('fa-times', !isOpen);
});

// Wait for the DOM to load
document.addEventListener("DOMContentLoaded", function() {
    const form = document.querySelector("form");
    const totalMarksDisplay = document.querySelector("#totalMarks");
    const markInputs = Array.from(document.querySelectorAll("input[type='number']"));
    
    // Calculate total marks dynamically
    const calculateTotalMarks = () => {
        const totalMarks = markInputs.reduce((sum, input) => sum + (parseFloat(input.value) || 0), 0);
        totalMarksDisplay.textContent = `Total Marks: ${totalMarks.toFixed(2)}`;
    };

    // Validate marks and remarks before form submission
    form.addEventListener("submit", function(event) {
        let isValid = true;
        
        markInputs.forEach(input => {
            const value = parseFloat(input.value);
            if (isNaN(value) || value < 0 || value > 100) {
                alert("Please enter a valid mark between 0 and 100.");
                isValid = false;
                return false;
            }
        });

        form.querySelectorAll("textarea").forEach(textarea => {
            if (textarea.value.length > 255) {
                alert("Remarks cannot be more than 255 characters.");
                isValid = false;
                return false;
            }
        });

        if (!isValid || !confirm("Are you sure you want to save these marks?")) {
            event.preventDefault();
        }
    });

    // Update total marks whenever an input value changes
    markInputs.forEach(input => input.addEventListener("input", calculateTotalMarks));

    // Reset form and total marks
    document.querySelector("#resetButton").addEventListener("click", () => {
        form.reset();
        totalMarksDisplay.textContent = "Total Marks: 0.00";
    });

    // Load existing marks if available
    if (typeof existingMarks !== 'undefined') {
        Object.entries(existingMarks).forEach(([topicId, evaluations]) => {
            Object.entries(evaluations).forEach(([evaluationId, mark]) => {
                const input = document.querySelector(`input[name="marks[${topicId}][${evaluationId}]"]`);
                if (input) input.value = mark;
            });
        });
    }

    // Initial total marks calculation based on loaded marks
    calculateTotalMarks();
});
