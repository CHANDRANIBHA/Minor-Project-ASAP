let messages = [];

// Function to open a new chat
function openChat(teacherName) {
    document.getElementById('chatWith').textContent = teacherName;

    const profileImage = document.getElementById('profileImage');
    switch (teacherName) {
        case 'Teacher 1':
            profileImage.src = 'profile1.webp';
            break;
        case 'Teacher 2':
            profileImage.src = 'profile2.jpg';
            break;
        case 'Teacher 3':
            profileImage.src = 'profile3.avif';
            break;
        default:
            profileImage.src = 'defaultProfile.jpg';
    }

    document.getElementById('chatMessages').innerHTML = '';
    messages = [];
}

// Function to send a message
function sendMessage() {
    const messageInput = document.getElementById('messageInput');
    const messageText = messageInput.value.trim();

    if (messageText !== "") {
        const timestamp = new Date();
        messages.push({ text: messageText, time: timestamp, isMine: true });
        renderMessages();
        messageInput.value = '';
    }
}

// Function to send a file message
function sendFileMessage(fileName, fileData) {
    const timestamp = new Date();
    messages.push({ text: fileName, time: timestamp, isMine: true, isFile: true, fileData });
    renderMessages();
}

// Function to handle file input
document.getElementById("fileButton").addEventListener("change", function () {
    const files = Array.from(this.files);
    files.forEach(file => {
        const reader = new FileReader();
        reader.onload = function (e) {
            sendFileMessage(file.name, e.target.result);
        };
        reader.readAsDataURL(file);
    });
    this.value = '';
});

// Function to render messages
function renderMessages() {
    const chatMessagesContainer = document.getElementById("chatMessages");
    chatMessagesContainer.innerHTML = ""; // Clear existing messages

    const messagesByDate = messages.reduce((acc, msg) => {
        const date = msg.time.toDateString();
        if (!acc[date]) {
            acc[date] = [];
        }
        acc[date].push(msg);
        return acc;
    }, {});

    for (const date in messagesByDate) {
        const dateHeader = document.createElement("div");
        dateHeader.textContent = date;
        dateHeader.classList.add("date-header");
        chatMessagesContainer.appendChild(dateHeader);

        messagesByDate[date].forEach(msg => displayMessage(msg));
    }

    chatMessagesContainer.scrollTop = chatMessagesContainer.scrollHeight; // Scroll to bottom
}

// Function to display a message
function displayMessage(msg) {
    const messageElement = document.createElement("div");
    messageElement.classList.add("message");
    if (msg.isMine) {
        messageElement.classList.add("me");
    }

    // Check if the message is a file
    if (msg.isFile) {
        const link = document.createElement("a");
        link.href = msg.fileData; // Set the href to the file data URL
        link.download = msg.text; // Set the download attribute to the file name
        link.textContent = msg.text; // Display the file name as clickable link
        messageElement.appendChild(link);
    } else {
        messageElement.textContent = msg.text; // For regular messages
    }

    // Display the timestamp
    const timestampElement = document.createElement("span");
    timestampElement.textContent = new Intl.DateTimeFormat('en-US', {
        hour: 'numeric',
        minute: 'numeric',
        hour12: true
    }).format(msg.time);
    timestampElement.classList.add("timestamp");

    const tickIcon = document.createElement("i");
    tickIcon.classList.add("fa", "fa-check", "sent-tick");
    messageElement.appendChild(tickIcon);
    
    messageElement.appendChild(timestampElement);

    setTimeout(() => {
        messageElement.style.opacity = 1; // Fade in the message
    }, 10); // Delay for transition effect

    document.getElementById("chatMessages").appendChild(messageElement);
}

// Event listener for the send button
document.getElementById('sendButton').addEventListener('click', sendMessage);

// Allow sending message with Enter key
document.getElementById('messageInput').addEventListener('keypress', function (e) {
    if (e.key === 'Enter') {
        sendMessage();
    }
});
