<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'G-Attendance') }}</title>

        {{-- Fonts & Icons --}}
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
        
        {{-- Vite & Styles --}}
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @stack('styles')
    </head>
    <body 
        x-data="{ 
            sidebarOpen: window.innerWidth > 1024, 
            isMobile: window.innerWidth < 1024 
        }" 
        @resize.window="isMobile = window.innerWidth < 1024; if(!isMobile) sidebarOpen = true;"
        class="font-sans antialiased"
    >
        <div class="min-h-screen bg-light-gray">
            
            {{-- Sidebar Navigation --}}
            @include('layouts.navigation')

            {{-- Main Content Wrapper --}}
            <div class="transition-all duration-300 ease-in-out" 
                 :class="{ 
                     'lg:ml-64': sidebarOpen, 
                     'lg:ml-20': !sidebarOpen 
                 }">
                
                {{-- Mobile Header with Hamburger Menu --}}
                <header class="sticky top-0 bg-white shadow-sm z-20 lg:hidden flex items-center justify-between h-16 px-4 sm:px-6">
                    <a href="{{ route('dashboard') }}" class="flex items-center space-x-3">
                        <div class="h-9 w-9 rounded-lg bg-primary-yellow flex items-center justify-center text-dark-blue font-bold text-xl">
                            G
                        </div>
                        <span class="font-bold text-xl text-dark-blue">G-Attendance</span>
                    </a>
                    <button @click="sidebarOpen = !sidebarOpen" class="p-2 rounded-md text-gray-500 hover:bg-gray-100 hover:text-gray-800 focus:outline-none">
                        <i class="fa-solid fa-bars text-xl"></i>
                    </button>
                </header>
                
                {{-- Page Content --}}
                <main>
                    {{-- Ganti padding di sini untuk mobile --}}
                    <div class="p-4 sm:p-6 lg:p-8">
                        {{ $slot }}
                    </div>
                </main>
            </div>
        </div>

        {{-- Scripts --}}
        <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        @stack('scripts')
    </body>
</html>