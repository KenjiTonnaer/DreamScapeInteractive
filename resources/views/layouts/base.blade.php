<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'DreamScape Interactive') }} - @yield('title', 'Home') </title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-gray-100 text-gray-900 flex flex-col">


    <header class="pt-4">
        <nav class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 h-16 grid grid-cols-3 items-center bg-white border border-gray-200 rounded-2xl shadow-sm">
            <div class="justify-self-start">
                <a href="{{ url('/') }}" class="font-semibold text-lg text-gray-900">
                    {{ config('app.name', 'DreamScape Interactive') }}
                </a>
            </div>

            <div class="justify-self-center flex items-center gap-6 text-sm">
                <a href="{{ url('/') }}" class="text-gray-700 hover:text-black hover:underline hover:underline-offset-3">Home</a>
                <a href="#" class="text-gray-700 hover:text-black hover:underline hover:underline-offset-3">Catalogus</a>
                <a href="#" class="text-gray-700 hover:text-black hover:underline hover:underline-offset-3">Inventaris</a>
                <a href="#" class="text-gray-700 hover:text-black hover:underline hover:underline-offset-3">Trades</a>
                <a href="#" class="text-gray-700 hover:text-black hover:underline hover:underline-offset-3">Contact</a>
            </div>

            <div class="justify-self-end flex items-center gap-4 text-sm">
                @auth
                    <span class="text-gray-500">{{ auth()->user()->username ?? auth()->user()->name }}</span>
                    <a href="{{ route('dashboard') }}" class="text-gray-700 hover:text-black hover:underline hover:underline-offset-3">Dashboard</a>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="px-3 py-1 rounded-lg border border-gray-300 bg-white hover:bg-gray-50">
                            Logout
                        </button>
                    </form>
                @endauth

                @guest
                    <a href="{{ route('login') }}" class="text-gray-700 hover:text-black hover:underline hover:underline-offset-3">Login</a>
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="px-3 py-1 rounded-lg bg-black text-white hover:bg-gray-800 hover:underline hover:underline-offset-3">Register</a>
                    @endif
                @endguest
            </div>
        </nav>
    </header>

    <main class="flex-1 max-w-6xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-8">
        @if (session('success'))
            <div class="mb-4 p-3 rounded-lg bg-gray-50 text-gray-800 border border-gray-200">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="mb-4 p-3 rounded-lg bg-gray-50 text-gray-800 border border-gray-200">
                {{ session('error') }}
            </div>
        @endif

        @yield('content')
    </main>

    <footer class="w-full mt-6 bg-white border-t border-gray-200">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-6 text-sm text-gray-600">
            <div class="flex flex-col items-center text-center gap-1">
                <span>&copy; {{ date('Y') }} DreamScape Interactive</span>
            </div>
        </div>
    </footer>
</body>
</html>
