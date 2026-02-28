@extends('layouts.app')

@section('content')

<div class="flex flex-col h-full">

    <div id="chat-box" class="flex-1 overflow-y-auto p-6 space-y-4">

        @foreach($messages as $msg)
            <div class="{{ $msg->role == 'user' ? 'text-right' : 'text-left' }}">
                <div class="inline-block p-3 rounded-lg
                    {{ $msg->role == 'user'
                        ? 'bg-purple-600 text-white'
                        : 'bg-gray-300 dark:bg-gray-700 dark:text-white' }}">
                    {{ $msg->message }}
                </div>
            </div>
        @endforeach

    </div>

    <div class="p-4 bg-gray-200 dark:bg-gray-800 flex gap-3">
        <input id="message"
               class="flex-1 p-3 rounded dark:bg-gray-700 dark:text-white"
               placeholder="Ask anything...">

        <button onclick="sendMessage()"
                class="bg-purple-600 text-white px-6 rounded">
            Send
        </button>
    </div>

</div>

<script>
async function sendMessage() {

    let input = document.getElementById('message');
    let message = input.value;
    if (!message.trim()) return;

    let chatBox = document.getElementById('chat-box');

    chatBox.innerHTML += `
        <div class="text-right">
            <div class="inline-block bg-purple-600 text-white p-3 rounded-lg">
                ${message}
            </div>
        </div>
    `;

    input.value = "";

    let aiDiv = document.createElement("div");
    aiDiv.className = "text-left";
    aiDiv.innerHTML = `
        <div class="inline-block bg-gray-300 dark:bg-gray-700 p-3 rounded-lg typing"></div>
    `;
    chatBox.appendChild(aiDiv);

    const response = await fetch("{{ route('chat.stream', $session->id) }}", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": "{{ csrf_token() }}"
        },
        body: JSON.stringify({ message: message })
    });

    const reader = response.body.getReader();
    const decoder = new TextDecoder();
    let aiText = "";

    while (true) {
        const { done, value } = await reader.read();
        if (done) break;

        aiText += decoder.decode(value);
        aiDiv.querySelector('.typing').innerText = aiText;
        chatBox.scrollTop = chatBox.scrollHeight;
    }
}
</script>

@endsection