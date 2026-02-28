<div class="fixed bottom-6 right-6 z-30">

    <button onclick="toggleWidget()"
        class="bg-purple-600 text-white p-4 rounded-full shadow-lg hover:scale-110 transition">
        💬
    </button>

    <div id="widgetBox"
         class="hidden mt-3 w-80 bg-white dark:bg-gray-800 shadow-2xl rounded-xl flex flex-col overflow-hidden">

        <div class="bg-purple-600 text-white p-3 font-semibold">
            Cherry Assistant
        </div>

        <div id="widgetMessages"
             class="flex-1 p-3 overflow-y-auto text-sm max-h-72 space-y-2">
        </div>

        <div class="p-3 border-t">
            <form id="widgetForm" class="flex gap-2">
                <input id="widgetInput"
                       class="flex-1 border rounded px-2 py-1 text-sm"
                       placeholder="Quick question...">
                <button class="bg-purple-600 text-white px-3 rounded">Send</button>
            </form>
        </div>

    </div>
</div>

<script>
function toggleWidget(){
    document.getElementById('widgetBox').classList.toggle('hidden');
}
</script>