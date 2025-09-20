@php
    // Membaca konfigurasi menu dari file config/menu.php
    $menuItems = config('menu');
@endphp

{{-- 
    Sidebar utama. 
    - Digerakkan oleh Alpine.js dengan state 'sidebarOpen'.
    - Lebar berubah secara dinamis (:class).
--}}
<aside
    class="fixed top-0 left-0 z-40 h-screen bg-dark-blue text-gray-300 transition-all duration-300 ease-in-out"
    :class="sidebarOpen ? 'w-64' : 'w-20'"
>
    <div class="flex flex-col h-full">
        <!-- Logo & Tombol Collapse -->
        <div class="h-16 flex items-center justify-between px-4 border-b border-gray-700/50 flex-shrink-0">
            <a href="{{ route('dashboard') }}" class="flex items-center space-x-3">
                <div class="h-9 w-9 rounded-lg bg-primary-yellow flex items-center justify-center text-dark-blue font-bold text-xl">
                    G
                </div>
                <span x-show="sidebarOpen" class="font-bold text-xl text-white transition-opacity duration-300 whitespace-nowrap">G-Attendance</span>
            </a>
            <button @click="sidebarOpen = !sidebarOpen" class="p-2 rounded-md hover:bg-gray-700 text-gray-400 focus:outline-none" x-show="sidebarOpen">
                 <i class="fa-solid fa-chevron-left"></i>
            </button>
        </div>

        <!-- Menu Links -->
        <nav class="flex-1 px-3 py-4 space-y-2 overflow-y-auto">
            @foreach ($menuItems as $item)
                @php
                    // Logika untuk menentukan apakah user boleh melihat menu ini
                    $userIsAdmin = Auth::user()->is_admin;
                    $canView = (in_array('admin', $item['role']) && $userIsAdmin) || in_array('user', $item['role']);
                @endphp

                @if ($canView)
                    {{-- Jika item adalah GRUP MENU dengan SUBMENU --}}
                    @if (isset($item['submenu']))
                        <div x-data="{ open: {{ request()->routeIs('admin.users.*', 'admin.shifts.*', 'admin.locations.*') ? 'true' : 'false' }} }">
                            <a href="#" @click.prevent="open = !open"
                                class="flex items-center justify-between w-full p-2 text-base font-normal rounded-lg hover:bg-gray-700 transition-colors duration-200">
                                <div class="flex items-center">
                                    <i class="{{ $item['icon'] }} w-6 h-6 flex-shrink-0 text-primary-yellow text-center"></i>
                                    <span class="ml-4" x-show="sidebarOpen">{{ $item['title'] }}</span>
                                </div>
                                <i class="fas fa-chevron-down transition-transform duration-200" :class="{'rotate-180': open, 'rotate-0': !open}" x-show="sidebarOpen"></i>
                            </a>
                            <ul x-show="open" x-collapse.duration.300ms class="pt-2 pl-6 space-y-2">
                                @foreach ($item['submenu'] as $subItem)
                                     <li>
                                        <a href="{{ route($subItem['route']) }}"
                                            class="flex items-center w-full p-2 text-sm font-normal rounded-lg hover:bg-gray-700 transition-colors duration-200 {{ request()->routeIs($subItem['route'].'*') ? 'bg-gray-700 text-white' : '' }}">
                                            <i class="{{ $subItem['icon'] }} w-6 h-6 flex-shrink-0 text-gray-400 text-center"></i>
                                            <span class="ml-4" x-show="sidebarOpen">{{ $subItem['title'] }}</span>
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    {{-- Jika item adalah LINK BIASA --}}
                    @else
                        <a href="{{ route($item['route']) }}"
                            class="flex items-center p-2 text-base font-normal rounded-lg hover:bg-gray-700 transition-colors duration-200 {{ request()->routeIs($item['route'].'*') ? 'bg-gray-700 text-white' : '' }}">
                            <i class="{{ $item['icon'] }} w-6 h-6 flex-shrink-0 text-primary-yellow text-center"></i>
                            <span class="ml-4" x-show="sidebarOpen">{{ $item['title'] }}</span>
                        </a>
                    @endif
                @endif
            @endforeach
        </nav>

        <!-- User Profile -->
        <div class="px-3 py-3 border-t border-gray-700/50 flex-shrink-0">
            <a href="{{ route('profile.edit') }}" class="flex items-center w-full p-2 rounded-lg hover:bg-gray-700 transition-colors duration-200">
                <img class="h-9 w-9 rounded-full object-cover flex-shrink-0" src="{{ Auth::user()->profile_photo_path ? asset('storage/' . Auth::user()->profile_photo_path) : 'https://ui-avatars.com/api/?name=' . urlencode(Auth::user()->name) . '&background=FCA311&color=14213D&bold=true' }}" alt="{{ Auth::user()->name }}">
                <div class="ml-4 overflow-hidden" x-show="sidebarOpen">
                    <p class="text-sm font-semibold text-white truncate">{{ Auth::user()->name }}</p>
                    <p class="text-xs text-gray-400 truncate">{{ Auth::user()->email }}</p>
                </div>
            </a>
        </div>
    </div>
</aside>
