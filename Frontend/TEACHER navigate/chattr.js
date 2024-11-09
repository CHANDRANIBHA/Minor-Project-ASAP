let userChats = {
    "KIRAN S": [],
    "MADHAV KRISHNA": [],
    "GEETHA C": []
};

  // Default to the first user
let lastMessageDate = null;  // Keep track of the last message's date

document.getElementById("send-btn").addEventListener("click", function() {
    sendMessage();
});

document.getElementById("chat-input").addEventListener("keydown", function(event) {
    if (event.key === "Enter") {
        sendMessage();
    }
});

// Automatically select the first user and load their chat on page load
window.onload = function() {
    document.getElementById("chat-header-name").textContent = currentUser;
    loadChatHistory(currentUser);
}

function selectUser(userName, registerNumber) {
    document.getElementById("chat-header-name").textContent = `${userName} (${registerNumber})`;
    currentUser = userName;
    loadChatHistory(userName);
}

function loadChatHistory(userName) {
    let chatMessages = document.getElementById("chat-messages");
    chatMessages.innerHTML = '';  // Clear current messages

    let messages = userChats[userName];
    messages.forEach(msg => {
        addMessage(msg.message, msg.type, msg.date, msg.status);
    });
}




function sendMessage() {
    const messageInput = document.getElementById('chat-input');
    const messageText = messageInput.value.trim();

    if (messageText) {
        // Create a new message element
        const messageElement = document.createElement('div');
        messageElement.classList.add('message', 'sent'); // Add 'sent' class for user message
        
        const textElement = document.createElement('div');
        textElement.classList.add('message-text');
        textElement.textContent = messageText;
        
        messageElement.appendChild(textElement);
        
        // Add the message to the chat area
        document.getElementById('chat-messages').appendChild(messageElement);
        
        // Clear the input box after sending the message
        messageInput.value = '';
        
        // Scroll to the bottom of the chat area
        document.getElementById('chat-messages').scrollTop = document.getElementById('chat-messages').scrollHeight;
    }
}






function sendMessage() {
    let message = document.getElementById("chat-input").value;
    if (message.trim() !== "" && currentUser) {
        const currentDate = new Date();
        addDateIfNeeded(currentDate);  // Check if we need to add a date separator
        addMessage(message, 'sent', currentDate, 'sent'); // Initially, set the status to 'sent'
        document.getElementById("chat-input").value = '';

        // Store the message for the current user
        userChats[currentUser].push({ message: message, type: 'sent', date: currentDate, status: 'sent' });

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
        // Simulate status updates for demonstration purposes
        setTimeout(() => updateMessageStatus(currentDate, 'delivered'), 2000);  // Simulate 'delivered' after 2 seconds
        setTimeout(() => updateMessageStatus(currentDate, 'read'), 5000);       // Simulate 'read' after 5 seconds
    }
}

function addMessage(text, type, date, status) {
    let chatMessages = document.getElementById("chat-messages");

    let messageDiv = document.createElement("div");
    messageDiv.classList.add("message", type);

    // Create a wrapper for time and status (to ensure alignment to the right)
    let timeStatusWrapper = document.createElement("div");
    timeStatusWrapper.style.display = 'flex';
    timeStatusWrapper.style.justifyContent = 'flex-end';

    let messageTime = document.createElement("span");
    messageTime.classList.add("message-time");
    messageTime.textContent = formatTime(date);

    let messageStatus = document.createElement("span");
    messageStatus.classList.add("message-status");
    updateMessageStatusElement(messageStatus, status);

    // Append the time and status to the wrapper
    timeStatusWrapper.appendChild(messageTime);
    timeStatusWrapper.appendChild(messageStatus);

    messageDiv.textContent = text;
    messageDiv.appendChild(timeStatusWrapper); // Add the time/status wrapper to the message
    chatMessages.appendChild(messageDiv);
    chatMessages.scrollTop = chatMessages.scrollHeight;  // Auto-scroll to the bottom
}

function updateMessageStatus(timestamp, newStatus) {
    let chatMessages = userChats[currentUser];
    
    // Find the message by its timestamp
    chatMessages.forEach(msg => {
        if (isSameDay(msg.date, timestamp) && formatTime(msg.date) === formatTime(timestamp)) {
            msg.status = newStatus;
        }
    });

    // Update the UI for the message status
    let messageDivs = document.getElementById("chat-messages").getElementsByClassName("message");
    for (let i = 0; i < messageDivs.length; i++) {
        let messageDiv = messageDivs[i];
        let messageTime = messageDiv.querySelector(".message-time").textContent;

        if (messageTime === formatTime(timestamp)) {
            let messageStatus = messageDiv.querySelector(".message-status");
            updateMessageStatusElement(messageStatus, newStatus);
        }
    }
}

function updateMessageStatusElement(element, status) {
    if (status === 'sent') {
        element.textContent = '✓';  // Single tick for sent
        element.classList.remove("blue-tick");
    } else if (status === 'delivered') {
        element.textContent = '✓✓';  // Double tick for delivered
        element.classList.remove("blue-tick");
    } else if (status === 'read') {
        element.textContent = '✓✓';  // Double blue tick for read
        element.classList.add("blue-tick");  // Blue color for read status
    }
}

function addDateIfNeeded(date) {
    let chatMessages = document.getElementById("chat-messages");

    // Check if the message belongs to a new day
    if (!lastMessageDate || !isSameDay(lastMessageDate, date)) {
        let dateDiv = document.createElement("div");
        dateDiv.classList.add("chat-date");
        dateDiv.textContent = formatDateLabel(date); // Add the label like "Today" or "Yesterday"
        chatMessages.appendChild(dateDiv);  // Insert the date above the message
        lastMessageDate = date;  // Update the last message date to the current one
    }
}


function formatDateLabel(date) {
    let today = new Date();
    let yesterday = new Date();
    yesterday.setDate(today.getDate() - 1);
    
    if (isSameDay(date, today)) {
        return "Today";
    } else if (isSameDay(date, yesterday)) {
        return "Yesterday";
    } else {
        // Format the date as "Day Month" (e.g., 13 Oct)
        return date.toLocaleDateString(undefined, { day: '2-digit', month: 'short' });
    }
}

function filterUsers() {
    let input = document.getElementById('search').value.toUpperCase();
    let usersList = document.getElementById("users-list").getElementsByClassName('user');
    
    for (let i = 0; i < usersList.length; i++) {
        let userName = usersList[i].textContent || usersList[i].innerText;
        usersList[i].style.display = userName.toUpperCase().indexOf(input) > -1 ? "" : "none";
    }
}

function formatTime(date) {
    return date.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
}

function isSameDay(date1, date2) {
    return date1.getFullYear() === date2.getFullYear() &&
           date1.getMonth() === date2.getMonth() &&
           date1.getDate() === date2.getDate();
}

function attachFile(event) {
    let file = event.target.files[0]; // Get the first selected file
    if (file) {
        const currentDate = new Date();
        addDateIfNeeded(currentDate); // Add date if needed

        // Create a URL for the file
        let fileUrl = URL.createObjectURL(file);
        addFileMessage(file.name, fileUrl, currentDate); // Add the file message
    }
}

function addFileMessage(fileName, fileUrl, date) {
    let chatMessages = document.getElementById("chat-messages");

    let messageDiv = document.createElement("div");
    messageDiv.classList.add("message", "file-message");

    let fileLink = document.createElement("a");
    fileLink.href = fileUrl;
    fileLink.textContent = fileName;
    fileLink.download = fileName; // Set the download attribute

    let forwardBtn = document.createElement("span");
    forwardBtn.classList.add("forward-btn");
    forwardBtn.innerHTML = "&#10150;"; // Forward icon
    forwardBtn.onclick = function() {
        forwardFile(fileName, fileUrl);
    };

    let messageTime = document.createElement("span");
    messageTime.classList.add("message-time");
    messageTime.textContent = formatTime(date);

    messageDiv.appendChild(fileLink); // Add the file link
    messageDiv.appendChild(forwardBtn); // Add the forward button
    messageDiv.appendChild(messageTime); // Add the time

    chatMessages.appendChild(messageDiv); // Add the message to chat
    chatMessages.scrollTop = chatMessages.scrollHeight; // Auto-scroll to the bottom
}
// Function for Back Button
function goBack(event) {
    event.stopPropogation()
    window.history.back();
}
