document.addEventListener("DOMContentLoaded", () => {
    const roomsList = document.getElementById("chat-rooms-list");
    const messagesContainer = document.getElementById("chat-messages");
    const messageForm = document.getElementById("message-form");
    const messageInput = document.getElementById("message-input");
    const chatInputContainer = document.getElementById("chat-input-container");
    const chatHeader = document.getElementById("chat-header-name");

    let currentRoom = null;

    async function loadRooms() {
        const res = await fetch("fetch_chat_rooms.php");
        const rooms = await res.json();

        roomsList.innerHTML = "";

        if (rooms.length === 0) {
            roomsList.innerHTML = `<div class='text-center text-muted py-4'>No conversations yet</div>`;
            return;
        }

        rooms.forEach(room => {
            const div = document.createElement("button");
            div.className = "list-group-item list-group-item-action d-flex align-items-center";
            div.innerHTML = `
                <img src="${room.profile_image || 'https://via.placeholder.com/40'}" class="rounded-circle me-2" width="40" height="40">
                <span>${room.other_user_email}</span>
            `;
            div.onclick = () => openRoom(room.chat_room_id, room.other_user_email);
            roomsList.appendChild(div);
        });
    }

    async function openRoom(roomId, otherUserEmail) {
        currentRoom = roomId;
        chatHeader.textContent = otherUserEmail;
        chatInputContainer.style.display = "block";

        const res = await fetch(`fetch_messages.php?room_id=${roomId}`);
        const messages = await res.json();

        renderMessages(messages);
    }

    function renderMessages(messages) {
        messagesContainer.innerHTML = "";
        messages.forEach(msg => {
            const div = document.createElement("div");
            div.className = "mb-2";
            div.innerHTML = `
                <div><strong>${msg.sender_email}</strong></div>
                <div>${msg.message}</div>
                <small class="text-muted">${msg.sent_at}</small>
            `;
            messagesContainer.appendChild(div);
        });
        messagesContainer.scrollTop = messagesContainer.scrollHeight;
    }

    messageForm.addEventListener("submit", async (e) => {
        e.preventDefault();
        if (!currentRoom) return;

        const message = messageInput.value.trim();
        if (message === "") return;

        await fetch("send_message.php", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ chat_room_id: currentRoom, message })
        });

        messageInput.value = "";
        openRoom(currentRoom, chatHeader.textContent); // Refresh chat
    });

    loadRooms();
});
