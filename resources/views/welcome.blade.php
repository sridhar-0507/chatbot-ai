<!DOCTYPE html>
<html>
<head>
    <title>Cherry Technology</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <script src="https://cdn.tailwindcss.com"></script>

    <!-- AOS Scroll Animation -->
    <link href="https://unpkg.com/aos@2.3.4/dist/aos.css" rel="stylesheet">
    <script src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script>

    <style>
        body {
            margin:0;
            overflow-x:hidden;
            font-family: ui-sans-serif;
        }

        /* Animated gradient */
        .bg-animated {
            background: linear-gradient(-45deg, #6d28d9, #9333ea, #4f46e5, #db2777);
            background-size: 400% 400%;
            animation: gradientMove 18s ease infinite;
        }

        @keyframes gradientMove {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        /* Floating blobs */
        .blob {
            position:absolute;
            border-radius:9999px;
            filter: blur(120px);
            opacity:0.6;
            animation: float 12s ease-in-out infinite;
        }

        .blob1 { width:400px;height:400px;background:#9333ea;top:-100px;left:-100px;}
        .blob2 { width:350px;height:350px;background:#db2777;bottom:-120px;right:-80px;animation-delay:3s;}

        @keyframes float {
            0%,100%{ transform:translateY(0px);}
            50%{ transform:translateY(-40px);}
        }

        /* Glass card */
        .glass {
            backdrop-filter: blur(30px);
            background: rgba(255,255,255,0.08);
            border:1px solid rgba(255,255,255,0.2);
        }

        /* Mouse glow */
        .glow {
            position: fixed;
            width: 500px;
            height: 500px;
            background: radial-gradient(circle, rgba(255,255,255,0.2), transparent 70%);
            pointer-events:none;
            transform:translate(-50%,-50%);
            transition: 0.08s linear;
            z-index:0;
        }

        /* Typing animation */
        .typing::after {
            content:"|";
            animation: blink 1s infinite;
        }

        @keyframes blink {
            0%{opacity:0}
            50%{opacity:1}
            100%{opacity:0}
        }
    </style>
</head>

<body class="bg-animated min-h-screen text-white relative">

<div id="glow" class="glow"></div>
<div class="blob blob1"></div>
<div class="blob blob2"></div>

<!-- HERO SECTION -->
<section class="min-h-screen flex flex-col justify-center items-center px-6 text-center relative z-10">

    <div class="glass p-10 md:p-16 rounded-3xl shadow-2xl max-w-4xl w-full"
         data-aos="zoom-in">

        <h1 class="text-4xl md:text-6xl font-extrabold mb-6 leading-tight">
            🍒 Cherry Technology
        </h1>

        <h2 id="typingText"
            class="text-lg md:text-2xl mb-8 font-semibold typing">
        </h2>

        <a href="{{ route('chat.page') }}"
           class="inline-block bg-white text-purple-700 px-8 md:px-12 py-3 md:py-4 rounded-full text-lg font-bold shadow-xl hover:scale-110 hover:shadow-2xl transition duration-300">
            Launch AI Chat →
        </a>

        <!-- Stats -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-12 text-sm md:text-base">

            <div data-aos="fade-up" class="glass p-6 rounded-2xl">
                ⚡ <strong>Ultra Fast</strong><br>
                Real-time AI engine
            </div>

            <div data-aos="fade-up" data-aos-delay="200" class="glass p-6 rounded-2xl">
                🧠 <strong>Context Aware</strong><br>
                Human-like conversations
            </div>

            <div data-aos="fade-up" data-aos-delay="400" class="glass p-6 rounded-2xl">
                🔒 <strong>Enterprise Ready</strong><br>
                Secure & scalable
            </div>

        </div>

    </div>

</section>

<!-- FEATURE SECTION -->
<section class="py-20 px-6 text-center relative z-10">

    <h2 class="text-3xl md:text-4xl font-bold mb-12" data-aos="fade-up">
        Why Cherry AI?
    </h2>

    <div class="grid md:grid-cols-3 gap-10 max-w-6xl mx-auto">

        <div class="glass p-8 rounded-2xl" data-aos="fade-up">
            🚀 AI Built For Startups
        </div>

        <div class="glass p-8 rounded-2xl" data-aos="fade-up" data-aos-delay="200">
            🎯 Designed Like GPT Pro
        </div>

        <div class="glass p-8 rounded-2xl" data-aos="fade-up" data-aos-delay="400">
            🌎 Global Scale Ready
        </div>

    </div>

</section>

@include('components.widget')

<script>
AOS.init({ once:true });

// Mouse glow
const glow = document.getElementById("glow");
document.addEventListener("mousemove", (e)=>{
    glow.style.left = e.clientX+"px";
    glow.style.top = e.clientY+"px";
});

// Typing text effect
const texts = [
    "The future of AI powered conversations.",
    "Fast. Intelligent. Scalable.",
    "Built for modern startups."
];

let index = 0;
let char = 0;
const typingElement = document.getElementById("typingText");

function typeEffect(){
    if(char < texts[index].length){
        typingElement.innerHTML += texts[index].charAt(char);
        char++;
        setTimeout(typeEffect,40);
    }else{
        setTimeout(()=>{
            typingElement.innerHTML="";
            char=0;
            index = (index+1)%texts.length;
            typeEffect();
        },2000);
    }
}

typeEffect();
</script>

</body>
</html>