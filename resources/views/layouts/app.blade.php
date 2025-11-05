<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>   

<body class="font-sans antialiased">
    <div class="min-h-screen bg-gray-100">
        <!-- Navbar -->
        <nav class="bg-white shadow px-4 py-3 flex justify-between items-center">
            <a href="{{ route('home') }}" class="text-xl font-bold">{{ config('app.name', 'Laravel') }}</a>
            
            <div class="flex items-center space-x-4">
                @guest
                    <a href="{{ route('login') }}" class="text-gray-700 hover:text-gray-900">Giriş Yap</a>
                    <a href="{{ route('register') }}" class="text-gray-700 hover:text-gray-900">Üye Ol</a>
                @else
                    <!-- Kullanıcı dropdown -->
                    <div class="relative inline-block text-left">
                        <button type="button" class="inline-flex justify-center w-full px-4 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300">
                            {{ Auth::user()->name }}
                            <i class="fa-solid fa-caret-down ml-2"></i>
                        </button>

                        <div class="origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 hidden group-hover:block" id="userDropdown">
                            <div class="py-1">
                                <a href="{{ route('dashboard') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Profil</a>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Çıkış Yap</button>
                                </form>
                            </div>
                        </div>
                    </div>
                    <script>
                        // Basit dropdown toggle
                        const dropdownButton = document.querySelector('button');
                        const dropdownMenu = document.getElementById('userDropdown');
                        dropdownButton.addEventListener('click', () => {
                            dropdownMenu.classList.toggle('hidden');
                        });
                    </script>
                @endguest
            </div>
        </nav>

        <!-- Page Heading -->
        @isset($header)
            <header class="bg-white shadow">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header>
        @endisset

        <!-- Page Content -->
        <main>
            {{ $slot }}
        </main>

        @stack('scripts')
    </div>
</body>
</html>
