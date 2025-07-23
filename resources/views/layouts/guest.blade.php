<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-gray-50">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>{{ $title ?? 'Form Kehadiran' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @filamentStyles
    @livewireStyles
</head>
<body class="antialiased h-full">
    <div class="min-h-screen flex items-center justify-center w-full p-6">
        {{-- ⬅️ Semua konten Livewire dibungkus satu div --}}
        @yield('content')
    </div>

    @livewireScripts
    @filamentScripts
</body>
</html>
