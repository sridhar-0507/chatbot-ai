<!DOCTYPE html>
<html>
<head>
    <title>Cherry AI Chat</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-900 text-white h-screen flex flex-col">

<!-- Header -->
<div class="bg-gray-800 p-4 text-xl font-bold text-center">
    🍒 Cherry AI Assistant
</div>

<!-- Chat Messages -->
<div id="chat-box" class="flex-1 overflow-y-auto p-6 space-y-4"></div>

<!-- Input -->
<div class="bg-gray-800 p-4 flex gap-3">
    <input id="message"
           type="text"
           placeholder="Type your message..."
           class="flex-1 p-3 rounded bg-gray-700 focus:outline-none">
    <button onclick="sendMessage()"
            class="bg-purple-600 px-6 rounded hover:bg-purple-700 transition">
        Send
    </button>
</div>

<script>
function sendMessage() {

    let message = document.getElementById('message').value;
    if(message.trim() === '') return;

    let chatBox = document.getElementById('chat-box');

    chatBox.innerHTML += `
        <div class="text-right">
            <div class="inline-block bg-purple-600 p-3 rounded-lg">
                ${message}
            </div>
        </div>
    `;

    document.getElementById('message').value = '';

    fetch("{{ route('ask') }}", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": "{{ csrf_token() }}"
        },
        body: JSON.stringify({ message: message })
    })
    .then(res => res.json())
    .then(data => {

        chatBox.innerHTML += `
            <div class="text-left">
                <div class="inline-block bg-gray-700 p-3 rounded-lg">
                    ${data.reply}
                </div>
            </div>
        `;

        chatBox.scrollTop = chatBox.scrollHeight;
    });
}
</script>

</body>
</html>