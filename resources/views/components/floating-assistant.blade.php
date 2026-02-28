<div x-data="{ open:false }">

    <!-- Floating Button -->
    <div @click="open = !open"
         class="fixed bottom-6 right-6 bg-purple-600 text-white w-14 h-14 flex items-center justify-center rounded-full shadow-lg cursor-pointer">
        🤖
    </div>

    <!-- Small Chat Popup -->
    <div x-show="open"
         class="fixed bottom-24 right-6 w-72 bg-white dark:bg-gray-800 rounded-xl shadow-xl flex flex-col">

        <div class="p-3 bg-purple-600 text-white rounded-t-xl">
            Quick AI Help
        </div>

        <div id="widget-messages"
             class="p-3 text-sm space-y-2 overflow-y-auto"
             style="max-height:250px;"></div>

        <div class="p-2 flex gap-2 border-t dark:border-gray-700">
            <input id="widget-input"
                   class="flex-1 border rounded p-2 dark:bg-gray-700 dark:text-white"
                   placeholder="Quick question...">

            <button onclick="sendWidgetMessage()"
                    class="bg-purple-600 text-white px-3 rounded">
                →
            </button>
        </div>
    </div>

</div>

<script>
async function sendWidgetMessage() {

    let input = document.getElementById('widget-input');
    let message = input.value;
    if(!message.trim()) return;

    const response = await fetch("{{ route('widget.ask') }}", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": "{{ csrf_token() }}"
        },
        body: JSON.stringify({ message: message })
    });

    const data = await response.json();

    let box = document.getElementById('widget-messages');

    box.innerHTML += `
        <div class="text-right">
            <span class="bg-purple-200 px-2 py-1 rounded inline-block">${message}</span>
        </div>
        <div class="text-left">
            <span class="bg-gray-200 dark:bg-gray-700 px-2 py-1 rounded inline-block">${data.reply}</span>
        </div>
    `;

    input.value = "";
}
</script>