<!DOCTYPE html>
<html>
<head>
    <title>Cherry Technology</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gradient-to-br from-purple-700 via-indigo-700 to-pink-600 min-h-screen flex flex-col text-white">

<div class="flex-1 flex flex-col justify-center items-center text-center px-6">

    <h1 class="text-6xl font-extrabold mb-6 animate-pulse">
        🍒 Cherry Technology
    </h1>

    <p class="text-xl max-w-2xl mb-10">
        Launching the next generation AI chatbot platform.
        Fast. Intelligent. Scalable.
    </p>

    <a href="{{ route('chat.new') }}"
       class="bg-white text-purple-700 px-10 py-4 rounded-full text-lg font-bold shadow-xl hover:scale-110 transition">
        Launch AI Chat
    </a>

</div>

@include('components.chatbot-widget')

</body>
</html>