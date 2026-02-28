<!DOCTYPE html>
<html>
<head>
    <title>Cherry AI</title>
    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        html, body { height:100%; }

        /* ===== THEMES ===== */
        .dark {
            background: linear-gradient(to bottom right, #0f172a, #020617);
            color: white;
        }

        body:not(.dark) {
            background: linear-gradient(to bottom right, #f8fafc, #e2e8f0);
            color: #111827;
        }

        .glass { backdrop-filter: blur(16px); }

        /* Sidebar collapse */
        .sidebar-collapsed {
            width: 80px !important;
        }

        .sidebar-collapsed .sidebar-text {
            display: none;
        }

        /* Mobile sidebar */
        @media (max-width: 768px) {
            #sidebar {
                position: absolute;
                z-index: 50;
                height: 100%;
                transform: translateX(-100%);
                transition: transform 0.3s ease;
            }

            #sidebar.mobile-open {
                transform: translateX(0);
            }
        }

        /* Chat bubble styling */
        .assistant-bubble {
            background: rgba(30,41,59,0.85);
            border: 1px solid #334155;
        }

        body:not(.dark) .assistant-bubble {
            background: #f1f5f9;
            border: 1px solid #e2e8f0;
        }

        .user-bubble {
            background: linear-gradient(135deg, #9333ea, #7e22ce);
            color:white;
        }

        .chat-input {
            background:#1e293b;
            border:1px solid #334155;
        }

        body:not(.dark) .chat-input {
            background:white;
            border:1px solid #d1d5db;
        }

        .typing::after {
            content: "...";
            animation: blink 1s infinite;
        }

        @keyframes blink {
            0%{opacity:0}
            50%{opacity:1}
            100%{opacity:0}
        }
    </style>
</head>

<body id="body" class="dark flex overflow-hidden">

<!-- ===== SIDEBAR ===== -->
<div id="sidebar"
     class="w-72 flex-shrink-0 flex flex-col transition-all duration-300 bg-slate-900 border-r border-slate-700">

    <div class="p-4 flex justify-between items-center">
        <span class="sidebar-text font-bold text-lg">🍒 Cherry AI</span>
        <button onclick="toggleSidebar()" class="text-xl">☰</button>
    </div>

    <a href="{{ route('chat.page') }}"
       class="mx-4 mb-4 bg-purple-600 text-white py-2 rounded text-center hover:scale-105 transition">
        <span class="sidebar-text">+ New Chat</span>
    </a>

    <div class="flex-1 overflow-y-auto px-4 space-y-2">

        @foreach($sessions as $s)
            <div class="flex justify-between items-center group">
                <a href="{{ route('chat.page', $s->session_id) }}"
                   class="truncate text-sm hover:text-purple-400 sidebar-text">
                    {{ $s->title ?? substr($s->session_id,0,8) }}
                </a>

                <form method="POST" action="{{ route('chat.delete',$s->session_id) }}">
                    @csrf
                    @method('DELETE')
                    <button class="text-xs text-red-400 sidebar-text">✕</button>
                </form>
            </div>
        @endforeach

    </div>
</div>

<!-- ===== MAIN AREA ===== -->
<div class="flex-1 flex flex-col">

    <!-- HEADER -->
    <div class="glass flex justify-between items-center px-6 py-4 border-b border-slate-700">

        <div class="font-semibold">
            {{ $currentSession->title ?? "New Chat" }}
        </div>

        <div class="flex items-center gap-4">

            <!-- FONT SIZE -->
            <div class="flex items-center gap-2">
                <span class="text-sm">A</span>
                <input type="range" min="14" max="22" value="16"
                       id="fontRange"
                       class="w-24">
                <span class="text-lg">A</span>
            </div>

            <!-- THEME -->
            <button onclick="toggleTheme()" class="text-xl">
                🌙
            </button>

        </div>
    </div>

    <!-- CHAT -->
    <div id="chatBox"
         class="flex-1 overflow-y-auto p-6 space-y-6"
         style="font-size:16px">

        @if($currentSession)
            @foreach($currentSession->messages as $msg)
                <div class="{{ $msg->role == 'user' ? 'text-right' : 'text-left' }}">
                    <div class="{{ $msg->role == 'user' ? 'user-bubble' : 'assistant-bubble' }}
                                inline-block px-5 py-4 rounded-2xl max-w-2xl leading-relaxed">

                        {!! nl2br(e($msg->message)) !!}

                        @if($msg->role == 'assistant')
                            <div class="text-xs mt-2 text-gray-400 cursor-pointer"
                                 onclick="copyText(this)">
                                📋 Copy
                            </div>
                        @endif

                    </div>
                </div>
            @endforeach
        @endif
    </div>

    <!-- INPUT -->
    <div class="p-4 flex items-center gap-3 border-t border-slate-700">
        <input id="messageInput"
               class="chat-input flex-1 rounded-xl p-4 outline-none focus:ring-2 focus:ring-purple-500"
               placeholder="Ask anything...">

        <button onclick="sendMessage()"
                class="bg-purple-600 px-6 py-3 rounded-xl hover:scale-105 transition text-white">
            Send
        </button>
    </div>

</div>

<script>

let sessionId = "{{ $currentSession->session_id ?? '' }}";

/* ===== SIDEBAR FIX ===== */
function toggleSidebar() {
    const sidebar = document.getElementById("sidebar");

    if (window.innerWidth <= 768) {
        sidebar.classList.toggle("mobile-open");
    } else {
        sidebar.classList.toggle("sidebar-collapsed");
    }
}

/* ===== THEME ===== */
function toggleTheme(){
    document.getElementById('body').classList.toggle("dark");
}

/* ===== FONT SIZE CONTROL ===== */
const range = document.getElementById("fontRange");
const chatBox = document.getElementById("chatBox");

range.addEventListener("input", function(){
    chatBox.style.fontSize = this.value + "px";
    localStorage.setItem("chatFontSize", this.value);
});

window.onload = function(){
    const saved = localStorage.getItem("chatFontSize");
    if(saved){
        range.value = saved;
        chatBox.style.fontSize = saved + "px";
    }
}

/* ===== COPY ===== */
function copyText(el){
    navigator.clipboard.writeText(
        el.parentElement.innerText.replace("📋 Copy","")
    );
    el.innerText="✅ Copied";
    setTimeout(()=> el.innerText="📋 Copy",2000);
}

/* ===== SEND MESSAGE ===== */
function sendMessage(){
    let msg = document.getElementById('messageInput').value;
    if(!msg) return;

    let box = document.getElementById('chatBox');

    box.innerHTML += `
        <div class="text-right">
            <div class="user-bubble inline-block px-5 py-4 rounded-2xl max-w-2xl">
                ${msg}
            </div>
        </div>
        <div class="text-left">
            <div class="assistant-bubble inline-block px-5 py-4 rounded-2xl max-w-2xl typing">
                AI is thinking
            </div>
        </div>
    `;

    fetch("{{ route('chat.send') }}", {
        method:"POST",
        headers:{
            "Content-Type":"application/json",
            "X-CSRF-TOKEN":"{{ csrf_token() }}"
        },
        body: JSON.stringify({
            message: msg,
            type: "gpt",
            session_id: sessionId
        })
    })
    .then(res=>res.json())
    .then(data=>{
        if(!sessionId){
            window.location.href = "/chat/" + data.session_id;
        } else {
            location.reload();
        }
    });

    document.getElementById('messageInput').value="";
}
</script>

</body>
</html>