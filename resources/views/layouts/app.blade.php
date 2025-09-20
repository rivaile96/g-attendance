<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'G-Attendance') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])

        @stack('styles')
    </head>
    <body x-data="{ sidebarOpen: true }" class="font-sans antialiased">
        <div class="min-h-screen bg-light-gray">
            
            @include('layouts.navigation')

            {{-- ▼▼▼ PERUBAHAN UTAMA ADA DI BARIS INI ▼▼▼ --}}
            <div class="transition-all duration-300 ease-in-out pl-20" :class="sidebarOpen ? 'lg:pl-64' : 'lg:pl-20'">
                
                {{-- Header Halaman (jika diperlukan di masa depan) --}}
                @if (isset($header))
                    <header class="bg-white shadow">
                        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                            {{ $header }}
                        </div>
                    </header>
                @endif
                
                <main>
                    <div class="py-12">
                        {{-- Hapus padding horizontal default agar konten bisa full-width --}}
                        <div class="mx-auto sm:px-6 lg:px-8">
                            {{ $slot }}
                        </div>
                    </div>
                </main>
            </div>
        </div>
        
        @stack('scripts')
    </body>
</html>