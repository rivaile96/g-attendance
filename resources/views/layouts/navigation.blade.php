@php
    $menuItems = config('menu');
@endphp

{{-- Sidebar --}}
<aside
    class="fixed top-0 left-0 z-40 h-screen bg-dark-blue text-gray-300 transition-all duration-300 ease-in-out"
    :class="{
        'w-64': sidebarOpen,
        'w-20': !sidebarOpen && !isMobile,
        'translate-x-0': sidebarOpen,
        '-translate-x-full': !sidebarOpen && isMobile
    }"
>
    <div class="flex flex-col h-full">
        <div class="h-16 flex items-center justify-between px-4 border-b border-gray-700/50 flex-shrink-0">
            <a href="{{ route('dashboard') }}" class="flex items-center space-x-3" :class="!sidebarOpen && !isMobile ? 'justify-center w-full' : ''">
                <div class="h-9 w-9 rounded-lg bg-primary-yellow flex items-center justify-center text-dark-blue font-bold text-xl flex-shrink-0">
                    G
                </div>
                <span x-show="sidebarOpen" class="font-bold text-xl text-white transition-opacity duration-300 whitespace-nowrap">G-Attendance</span>
            </a>
            <button @click="sidebarOpen = !sidebarOpen" class="p-2 rounded-md hover:bg-gray-700 text-gray-400 focus:outline-none lg:block hidden" x-show="sidebarOpen">
                 <i class="fa-solid fa-chevron-left"></i>
            </button>
        </div>

        <nav class="flex-1 px-3 py-4 space-y-2 overflow-y-auto">
            {{-- ... (KODE MENU LINKS TETAP SAMA, TIDAK PERLU DIUBAH) ... --}}
             @foreach ($menuItems as $item)
                @php
                    $canView = (in_array('admin', $item['role']) && Auth::user()->is_admin) || in_array('user', $item['role']);
                    
                    $isSubmenuActive = false;
                    if (isset($item['submenu'])) {
                        $isSubmenuActive = collect($item['submenu'])->contains(function ($subItem) {
                            return request()->routeIs($subItem['route'].'*');
                        });
                    }
                @endphp

                @if ($canView)
                    @if (isset($item['submenu']))
                        <div x-data="{ open: {{ $isSubmenuActive ? 'true' : 'false' }} }">
                            <a href="#" @click.prevent="open = !open"
                                class="flex items-center justify-between w-full p-2 text-base font-normal rounded-lg hover:bg-gray-700 transition-colors duration-200">
                                <div class="flex items-center">
                                    <i class="{{ $item['icon'] }} w-6 h-6 flex-shrink-0 text-primary-yellow text-center transition-all" :class="!sidebarOpen && !isMobile ? 'mx-auto' : ''"></i>
                                    <span class="ml-4" x-show="sidebarOpen">{{ $item['title'] }}</span>
                                </div>
                                <i class="fas fa-chevron-down transition-transform duration-200" :class="{'rotate-180': open, 'rotate-0': !open}" x-show="sidebarOpen"></i>
                            </a>
                            <ul x-show="open && sidebarOpen" x-collapse.duration.300ms class="pt-2 pl-4 space-y-2">
                                @foreach ($item['submenu'] as $subItem)
                                     <li>
                                        <a href="{{ route($subItem['route']) }}"
                                            class="flex items-center w-full p-2 text-sm font-normal rounded-lg hover:bg-gray-700 transition-colors duration-200 {{ request()->routeIs($subItem['route'].'*') ? 'bg-gray-700 text-white' : '' }}">
                                            <i class="{{ $subItem['icon'] }} w-6 h-6 flex-shrink-0 text-gray-400 text-center"></i>
                                            <span class="ml-4">{{ $subItem['title'] }}</span>
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @else
                        <a href="{{ route($item['route']) }}"
                            class="flex items-center p-2 text-base font-normal rounded-lg hover:bg-gray-700 transition-colors duration-200 {{ request()->routeIs($item['route'].'*') ? 'bg-gray-700 text-white' : '' }}">
                            <i class="{{ $item['icon'] }} w-6 h-6 flex-shrink-0 text-primary-yellow text-center transition-all" :class="!sidebarOpen && !isMobile ? 'mx-auto' : ''"></i>
                            <span class="ml-4" x-show="sidebarOpen">{{ $item['title'] }}</span>
                        </a>
                    @endif
                @endif
            @endforeach
        </nav>

        {{-- ▼▼▼ PERUBAHAN DIMULAI DARI SINI ▼▼▼ --}}
        <div class="px-3 py-3 border-t border-gray-700/50 flex-shrink-0">
             <a href="{{ route('profile.edit') }}" class="flex items-center w-full p-2 rounded-lg hover:bg-gray-700 transition-colors duration-200" :class="!sidebarOpen && !isMobile ? 'justify-center' : ''">
                <img class="h-9 w-9 rounded-full object-cover flex-shrink-0" src="{{ Auth::user()->profile_photo_path ? asset('storage/' . Auth::user()->profile_photo_path) : 'https://ui-avatars.com/api/?name=' . urlencode(Auth::user()->name) . '&background=FCA311&color=14213D&bold=true' }}" alt="{{ Auth::user()->name }}">
                <div class="ml-4 overflow-hidden" x-show="sidebarOpen">
                    <p class="text-sm font-semibold text-white truncate">{{ Auth::user()->name }}</p>
                    <p class="text-xs text-gray-400 truncate">{{ Auth::user()->email }}</p>
                </div>
            </a>

            <form method="POST" action="{{ route('logout') }}" class="mt-2">
                @csrf
                <button type="submit" class="flex items-center w-full p-2 text-base font-normal rounded-lg text-red-400 hover:bg-red-500 hover:text-white transition-colors duration-200" :class="!sidebarOpen && !isMobile ? 'justify-center' : ''">
                    <i class="fa-solid fa-right-from-bracket w-6 h-6 flex-shrink-0 text-center transition-all" :class="!sidebarOpen && !isMobile ? 'mx-auto' : ''"></i>
                    <span class="ml-4" x-show="sidebarOpen">Logout</span>
                </button>
            </form>
        </div>
        {{-- ▲▲▲ PERUBAHAN SELESAI DI SINI ▲▲▲ --}}
    </div>
</aside>

{{-- Overlay for mobile --}}
<div x-show="sidebarOpen && isMobile" @click="sidebarOpen = false" class="fixed inset-0 bg-black opacity-50 z-30 lg:hidden" x-transition:enter="transition-opacity ease-linear duration-300" x-transition:enter-start="opacity-0" x-transition:leave="transition-opacity ease-linear duration-300" x-transition:leave-end="opacity-0" style="display: none;"></div>