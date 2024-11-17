// Load user list dynamically
function loadUserList() {
    const userList = document.getElementById("userList");
    const currentUserId = document.getElementById("currentUserId").value;

    fetch("fetch_users.php") // Create a script to fetch all users except the current user
        .then(response => response.json())
        .then(users => {
            userList.innerHTML = users.map(user => `
                <li onclick="openChat('${user.user_id}', '${user.user_name}')">${user.user_name}</li>
            `).join("");
        });
}

// Open chat
function openChat(receiverId, userName) {
    document.getElementById("receiverId").value = receiverId;
    document.getElementById("chatWith").textContent = `Chat with ${userName}`;
    fetchMessages(receiverId);
}

// Fetch messages
function fetchMessages(receiverId) {
    const senderId = document.getElementById("currentUserId").value;

    fetch(`fetch_messages.php?sender_id=${senderId}&receiver_id=${receiverId}`)
        .then(response => response.json())
        .then(data => {
            const chatMessages = document.getElementById("chatMessages");
            chatMessages.innerHTML = data.messages.map(msg => `
                <div class="${msg.sender_id === senderId ? 'message-sent' : 'message-received'}">
                    <p>${msg.message_content}</p>
                    ${msg.attachment_path ? `<a href="${msg.attachment_path}" download>Attachment</a>` : ''}
                </div>
            `).join("");
        });
}

// Send message
document.getElementById("messageForm").addEventListener("submit", function (event) {
    event.preventDefault();
    const formData = new FormData(this);

    fetch("send_messages.php", {
        method: "POST",
        body: formData
    })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                fetchMessages(document.getElementById("receiverId").value);
                document.getElementById("messageInput").value = "";
            } else {
                alert(data.error);
            }
        });
});

// Load user list on page load
document.addEventListener("DOMContentLoaded", loadUserList);
