document.addEventListener("DOMContentLoaded", () => {
    const form = document.getElementById("trainingForm");
    const semesterDropdown = document.getElementById("semester");
    const classList = document.getElementById("classList");
    const popup = document.getElementById("popup");
    const overlay = document.getElementById("overlay");
    const confirmButton = document.getElementById("confirmButton");
    const cancelButton = document.getElementById("cancelButton");
    const confirmationDetails = document.getElementById("confirmationDetails");

    // Fetch classes based on semester
    semesterDropdown.addEventListener("change", () => {
        const semester = semesterDropdown.value;
        if (semester) {
            fetch(`fetch_classes.php?semester=${semester}`)
                .then((response) => response.json())
                .then((data) => {
                    if (data.error) {
                        console.error(data.error);
                        classList.innerHTML = "<p>Error fetching classes.</p>";
                    } else {
                        classList.innerHTML = ""; // Clear previous classes
                        data.forEach((classItem) => {
                            const checkbox = document.createElement("input");
                            checkbox.type = "checkbox";
                            checkbox.name = "classes[]";
                            checkbox.value = classItem.class_id;

                            const label = document.createElement("label");
                            label.textContent = classItem.class_name;

                            label.insertBefore(checkbox, label.firstChild);
                            classList.appendChild(label);
                        });
                    }
                })
                .catch((error) => {
                    console.error("Fetch error:", error);
                    classList.innerHTML = "<p>Error loading classes.</p>";
                });
        } else {
            classList.innerHTML = "";
        }
    });

    // Handle form submission
    form.addEventListener("submit", (event) => {
        event.preventDefault(); // Prevent the default form submission

        const formData = new FormData(form);

        // Verify if all required fields are populated
        const requiredFields = ['activity', 'semester', 'studentCount', 'sessionTime', 'classes[]'];
        const missingFields = [];

        requiredFields.forEach(field => {
            if (!formData.has(field) || formData.getAll(field).length === 0) {
                missingFields.push(field);
            }
        });

        if (missingFields.length > 0) {
            alert("Error: Missing required fields - " + missingFields.join(", "));
            return; // Stop the form submission if there are missing fields
        }

        // Show the confirmation details in the popup
        confirmationDetails.innerHTML = `
            <p><b>Activity:</b> ${formData.get("activity")}</p>
            <p><b>Semester:</b> ${formData.get("semester")}</p>
            <p><b>Classes:</b> ${[...formData.getAll("classes[]")].join(", ")}</p>
            <p><b>Student Count:</b> ${formData.get("studentCount")}</p>
            <p><b>Session Time:</b> ${formData.get("sessionTime")}</p>
            <p><b>Session Link:</b> ${formData.get("sessionLink")}</p>
            <p><b>Description:</b> ${formData.get("description")}</p>
        `;

        popup.style.display = "block";
        overlay.style.display = "block";
    });

    // Close popup
    cancelButton.addEventListener("click", () => {
        popup.style.display = "none";
        overlay.style.display = "none";
    });

    // Confirm and send data to backend
    confirmButton.addEventListener("click", () => {
        const formData = new FormData(form);
        fetch("prof.php", {
            method: "POST",
            body: formData,
        })
            .then((response) => response.text())
            .then((result) => {
                alert(result);
                popup.style.display = "none";
                overlay.style.display = "none";
                form.reset();
                classList.innerHTML = ""; // Clear classes
            })
            .catch((error) => console.error("Error:", error));
    });
});
