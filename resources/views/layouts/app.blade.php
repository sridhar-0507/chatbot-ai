<!DOCTYPE html>
<html lang="en" x-data="{ darkMode: true }" :class="darkMode ? 'dark' : ''">
<head>
    <meta charset="UTF-8">
    <title>Cherry AI</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <script src="//unpkg.com/alpinejs" defer></script>

    <script>
        tailwind.config = { darkMode: 'class' }
    </script>
</head>

<body class="bg-gray-100 dark:bg-gray-900 transition">

<div class="flex h-screen relative">

    <!-- Sidebar -->
    <div class="w-64 bg-gray-200 dark:bg-gray-800 p-4 flex flex-col">

        <div class="flex justify-between items-center mb-4">
            <h2 class="font-bold dark:text-white">🍒 Cherry AI</h2>
            <button @click="darkMode = !darkMode"
                class="bg-gray-300 dark:bg-gray-700 px-2 py-1 rounded text-sm">
                Toggle
            </button>
        </div>

        <a href="{{ route('chat.new') }}"
           class="bg-purple-600 text-white p-2 rounded text-center mb-4">
           + New Chat
        </a>

        <div class="overflow-y-auto flex-1 space-y-2">

            @foreach($sessions as $s)
                <a href="{{ route('chat.show', $s->id) }}"
                   class="block p-2 rounded text-sm
                   {{ isset($session) && $session->id == $s->id
                        ? 'bg-purple-600 text-white'
                        : 'bg-gray-300 dark:bg-gray-700 dark:text-white' }}">
                    Chat #{{ $s->id }}
                </a>
            @endforeach

        </div>
    </div>

    <div class="flex-1">
        @yield('content')
    </div>

</div>

@include('components.widget')

</body>
</html>