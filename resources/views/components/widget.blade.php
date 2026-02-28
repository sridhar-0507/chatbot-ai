<div id="widgetBtn"
     class="fixed bottom-6 right-6 bg-purple-600 w-14 h-14 rounded-full flex items-center justify-center cursor-pointer shadow-lg">
     🤖
</div>

<div id="widgetBox"
     class="hidden fixed bottom-24 right-6 w-80 bg-white text-black rounded-xl shadow-xl flex flex-col">

    <div class="bg-purple-600 text-white p-3 rounded-t-xl">
        Cherry AI Assistant
    </div>

    <div id="widgetChat" class="flex-1 p-3 overflow-y-auto text-sm space-y-2"></div>

    <div class="p-2 flex">
        <input id="widgetInput"
               class="flex-1 border p-2 rounded text-sm"
               placeholder="Quick question...">
        <button onclick="sendWidget()" class="ml-2 bg-purple-600 text-white px-3 rounded">
            →
        </button>
    </div>
</div>

<script>
let widgetSession = null;

document.getElementById('widgetBtn').onclick = () => {
    document.getElementById('widgetBox').classList.toggle('hidden');
}

function sendWidget(){
    let msg = document.getElementById('widgetInput').value;
    if(!msg) return;

    fetch("{{ route('chat.send') }}", {
        method:"POST",
        headers:{
            "Content-Type":"application/json",
            "X-CSRF-TOKEN":"{{ csrf_token() }}"
        },
        body: JSON.stringify({
            message: msg,
            type: "widget",
            session_id: widgetSession
        })
    })
    .then(res=>res.json())
    .then(data=>{
        widgetSession = data.session_id;

        document.getElementById('widgetChat').innerHTML +=
            `<div class="text-right text-purple-600">${msg}</div>`;

        document.getElementById('widgetChat').innerHTML +=
            `<div class="text-left text-gray-700">${data.reply}</div>`;

        document.getElementById('widgetInput').value="";
    });
}
</script>