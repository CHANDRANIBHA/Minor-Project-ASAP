// Function to toggle the sidebar menu
document.getElementById('menuToggle').addEventListener('click', function () {
    const sidebar = document.getElementById('sidebar');
    const isOpen = sidebar.style.width === '250px';
    sidebar.style.width = isOpen ? '0' : '250px';
    this.querySelector('i').classList.toggle('fa-bars', isOpen);
    this.querySelector('i').classList.toggle('fa-times', !isOpen);
});

// Function to calculate totals for the marks table
const calculateTotal = () => {
    let grandQuantTotal = 0;
    let grandLRTotal = 0;
    let grandTotal = 0;

    // Iterate through each row and calculate totals
    document.querySelectorAll('tbody tr').forEach(row => {
        const quantInput = row.querySelector('input[data-type="quant"]');
        const lrInput = row.querySelector('input[data-type="lr"]');
        const maxQuant = parseFloat(quantInput.getAttribute('max'));
        const maxLR = parseFloat(lrInput.getAttribute('max'));

        let quant = parseFloat(quantInput.value) || 0;
        let lr = parseFloat(lrInput.value) || 0;

        // Enforce max value for Quantitative and LR
        if (quant > maxQuant) quant = maxQuant;
        if (lr > maxLR) lr = maxLR;

        quantInput.value = quant;
        lrInput.value = lr;

        const rowTotal = quant + lr;
        row.querySelector('.row-total').innerText = rowTotal;

        grandQuantTotal += quant;
        grandLRTotal += lr;
        grandTotal += rowTotal;
    });

    // Update the grand total fields
    document.getElementById('quant-grand-total').innerText = grandQuantTotal;
    document.getElementById('lr-grand-total').innerText = grandLRTotal;
    document.getElementById('grand-total').innerText = grandTotal;
};

// Event listener for recalculating totals when input values change
document.querySelectorAll('.marks-input').forEach(input => {
    input.addEventListener('input', calculateTotal);
});

// Initial calculation of totals
calculateTotal();

// Function to collect all data from the marks table (previously defined)
function collectTableData() {
    // Prepare an array to hold all mark entries
    let data = [];

    // Calculate totals before collecting data
    calculateTotal();

    // Iterate over each row in the table (excluding the header and Grand Total row)
    let rows = document.querySelectorAll("#marksTable tbody tr");

    rows.forEach(row => {
        // Get evaluation name from the first cell
        let evaluation_name = row.querySelector(".evaluation-name").textContent.trim();

        // Iterate over topics (e.g., Quantitative, LR)
        let quantitativeMark = row.querySelector(".quantitative-mark input").value || 0;
        let lrMark = row.querySelector(".lr-mark input").value || 0;
        
        // Get remarks if any
        let remark = row.querySelector(".remarks input").value || '';

        // Create data entry for Quantitative
        if (quantitativeMark !== '0') {
            data.push({
                topic_name: 'quantitative',
                evaluation_name: evaluation_name,
                mark: parseFloat(quantitativeMark),
                remark: remark
            });
        }

        // Create data entry for LR
        if (lrMark !== '0') {
            data.push({
                topic_name: 'lr',
                evaluation_name: evaluation_name,
                mark: parseFloat(lrMark),
                remark: remark
            });
        }
    });

    // Send data to the server via AJAX
    saveMarksToDatabase(data);
}

// Function to save collected marks data to the database using AJAX
function saveMarksToDatabase(data) {
    // Use AJAX to send the data to the server (aptimarks.php)
    fetch('aptimarks.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(responseData => {
        alert(responseData.message);
    })
    .catch(error => {
        console.error('Error:', error);
    });
}

// Attach the click event to the Save button if not inline
document.querySelector("#saveButton").addEventListener("click", collectTableData);
