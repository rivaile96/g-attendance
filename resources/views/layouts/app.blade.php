<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'G-Attendance') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Styles -->
        @vite(['resources/css/app.css'])

        {{-- Slot ini penting untuk memuat CSS spesifik halaman, seperti Leaflet.js --}}
        @stack('styles')

        <!-- Scripts -->
        @vite(['resources/js/app.js'])
        
        {{-- Slot ini penting untuk memuat JS library (seperti Leaflet.js) sebelum <body> --}}
        @stack('head-scripts')
    </head>
    <body class="font-sans antialiased">
        {{-- Pembungkus utama dengan state AlpineJS, tinggi layar penuh, dan overflow tersembunyi --}}
        <div x-data="{ sidebarOpen: true }" class="relative h-screen flex overflow-hidden bg-slate-100">
            
            <!-- Sidebar -->
            @include('layouts.navigation')

            <!-- Konten Utama - Area ini yang bisa di-scroll secara independen -->
            <main class="flex-1 overflow-y-auto p-4 sm:p-6 lg:p-8">
                {{ $slot }}
            </main>
        </div>

        {{-- Slot ini penting untuk memuat JavaScript inisialisasi di akhir halaman --}}
        @stack('scripts')
    </body>
</html>

