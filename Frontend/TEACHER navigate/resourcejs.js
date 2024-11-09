function searchNotes() {
    let input = document.getElementById('searchBar').value.toLowerCase();
    let notes = document.getElementsByClassName('note');
  
    for (let i = 0; i < notes.length; i++) {
      let note = notes[i].getElementsByClassName('note-title')[0];
      if (note.innerHTML.toLowerCase().includes(input)) {
        notes[i].style.display = '';
      } else {
        notes[i].style.display = 'none';
      }
    }
  }

// Initialize an empty notes array
let notes = [];

// Function to render the list of notes dynamically
function renderNotes(filteredNotes = notes) {
    let notesSection = document.getElementById('notes-section');
    notesSection.innerHTML = '';

    filteredNotes.forEach(note => {
        notesSection.innerHTML += `
            <div class="note-card" onclick="openFile('${note.fileUrl}')">
                <p class="note-title">${note.title}</p>
                <span class="stylish-button" onclick="downloadFile('${note.fileUrl}'); event.stopPropagation();">
                    <i class="fas fa-download"></i>
                </span>
            </div>
        `;
    });
}

// Function to open the file
function openFile(url) {
    window.open(url, '_blank');
}

// Function to download the file
function downloadFile(url) {
    const a = document.createElement('a');
    a.href = url;
    a.download = ''; // This will prompt the user to download the file
    document.body.appendChild(a);
    a.click();
    document.body.removeChild(a);
}

// Function to handle file upload
function uploadFile() {
    const fileInput = document.getElementById('attachFile');
    const file = fileInput.files[0];

    if (file) {
        const fileUrl = URL.createObjectURL(file); // Create a URL for the uploaded file
        const noteTitle = file.name; // Use the file name as the note title
        notes.push({ title: noteTitle, fileUrl: fileUrl }); // Add to notes array
        renderNotes(); // Re-render notes to include the new file
        fileInput.value = ''; // Clear the file input
    }
}

// Search function
function searchNotes() {
    const searchInput = document.getElementById('searchBar').value.toLowerCase();
    const filteredNotes = notes.filter(note => note.title.toLowerCase().includes(searchInput));
    renderNotes(filteredNotes);
}

// Initial render
renderNotes();

  let userRole = 'teacher';  // This value can be dynamically set based on the logged-in user

  if (userRole === 'teacher') {
    document.getElementById('attachButton').style.display = 'block';
  }
  

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
        window.location.href = 'resoaptitude.html'; // Redirect to Aptitude Resources
    } else if (page === 'verbal') {
        window.location.href = 'resoverbal.html'; // Redirect to Verbal Resources
    } else if (page === 'softskills') {
        window.location.href = 'resosoftskills.html'; // Redirect to Soft Skills Resources
    } else if (page === 'training') {
        window.location.href = 'resotraining.html'; // Redirect to Personal Training Resources
    }
}
// Function for Back Button
function goBack() {
    window.history.back();
}

// Function for Logout
function logout() {
        window.location.href = 'home.html'; 
    }



