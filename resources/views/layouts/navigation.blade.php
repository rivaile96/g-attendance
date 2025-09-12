<div x-data="{ sidebarOpen: false }" class="relative z-50">

    {{-- Overlay untuk Mobile --}}
    <div x-show="sidebarOpen"
         x-transition.opacity
         class="fixed inset-0 bg-black/50 backdrop-blur-sm lg:hidden"
         @click="sidebarOpen = false"></div>

    {{-- Sidebar --}}
    <aside :class="sidebarOpen ? 'w-64' : 'w-20'"
       class="h-screen flex flex-col flex-shrink-0 bg-gradient-to-b from-slate-800 to-slate-900 text-white transition-all duration-300 rounded-r-2xl shadow-lg">

        {{-- Header --}}
        <div class="h-16 flex items-center justify-between px-4 border-b border-slate-700/70">
            <a href="{{ route('dashboard') }}" class="flex items-center w-full space-x-2">
                <div class="h-9 w-9 rounded-xl bg-primary-yellow flex items-center justify-center text-dark-blue font-bold">G</div>
                <h1 x-show="sidebarOpen" x-transition
                    class="text-lg font-bold tracking-wide">G-Attendance</h1>
            </a>
            <button @click="sidebarOpen = !sidebarOpen"
                    class="p-2 rounded-md hover:bg-slate-700 text-slate-300 hover:text-white focus:outline-none">
                <svg x-show="sidebarOpen" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                <svg x-show="!sidebarOpen" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </button>
        </div>

        {{-- Navigation --}}
        <nav class="flex-grow px-3 py-5 space-y-2">
            @php
                $dashboardActive  = request()->routeIs('dashboard');
                $attendanceActive = request()->routeIs('attendance.index');
                $locationActive   = request()->routeIs('admin.locations.*');
                $userActive       = request()->routeIs('admin.users.*');
                $reportActive     = request()->routeIs('reports.*');
            @endphp

            {{-- Dashboard --}}
            <a href="{{ route('dashboard') }}"
               class="flex items-center px-3 py-3 rounded-xl transition-all duration-200
                      {{ $dashboardActive ? 'bg-slate-700 text-primary-yellow' : 'text-slate-300 hover:bg-slate-700 hover:text-white' }}">
                <span class="w-1 h-6 rounded-full {{ $dashboardActive ? 'bg-primary-yellow' : 'bg-transparent' }}"></span>
                <svg class="w-6 h-6 ml-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
                <span x-show="sidebarOpen" class="ml-3 font-semibold whitespace-nowrap">Dashboard</span>
            </a>

            {{-- Absensi --}}
            <a href="{{ route('attendance.index') }}"
               class="flex items-center px-3 py-3 rounded-xl transition-all duration-200
                      {{ $attendanceActive ? 'bg-slate-700 text-primary-yellow' : 'text-slate-300 hover:bg-slate-700 hover:text-white' }}">
                <span class="w-1 h-6 rounded-full {{ $attendanceActive ? 'bg-primary-yellow' : 'bg-transparent' }}"></span>
                <svg class="w-6 h-6 ml-3 flex-shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                </svg>
                <span x-show="sidebarOpen" class="ml-3 font-semibold whitespace-nowrap">Absensi</span>
            </a>

            {{-- Manajemen Lokasi --}}
            <a href="{{ route('admin.locations.index') }}"
               class="flex items-center px-3 py-3 rounded-xl transition-all duration-200
                      {{ $locationActive ? 'bg-slate-700 text-primary-yellow' : 'text-slate-300 hover:bg-slate-700 hover:text-white' }}">
                <span class="w-1 h-6 rounded-full {{ $locationActive ? 'bg-primary-yellow' : 'bg-transparent' }}"></span>
                <svg class="w-6 h-6 ml-3 flex-shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" />
                </svg>
                <span x-show="sidebarOpen" class="ml-3 font-semibold whitespace-nowrap">Manajemen Lokasi</span>
            </a>

            {{-- Manajemen Karyawan --}}
            <a href="{{ route('admin.users.index') }}"
               class="flex items-center px-3 py-3 rounded-xl transition-all duration-200
                      {{ $userActive ? 'bg-slate-700 text-primary-yellow' : 'text-slate-300 hover:bg-slate-700 hover:text-white' }}">
                <span class="w-1 h-6 rounded-full {{ $userActive ? 'bg-primary-yellow' : 'bg-transparent' }}"></span>
                <svg class="w-6 h-6 ml-3 flex-shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-4.67c.12-.24.232-.487.34-.737m-1.06-2.553a6.375 6.375 0 00-4.28-1.59M12 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0z" />
                </svg>
                <span x-show="sidebarOpen" class="ml-3 font-semibold whitespace-nowrap">Manajemen Karyawan</span>
            </a>

            {{-- Laporan Absensi --}}
            <a href="{{ route('reports.attendances') }}"
               class="flex items-center px-3 py-3 rounded-xl transition-all duration-200
                      {{ $reportActive ? 'bg-slate-700 text-primary-yellow' : 'text-slate-300 hover:bg-slate-700 hover:text-white' }}">
                <span class="w-1 h-6 rounded-full {{ $reportActive ? 'bg-primary-yellow' : 'bg-transparent' }}"></span>
                <svg class="w-6 h-6 ml-3 flex-shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.375 19.5h17.25m-17.25 0a1.125 1.125 0 01-1.125-1.125v-1.5c0-.621.504-1.125 1.125-1.125H6.75m11.25 0h-4.5m4.5 0a1.125 1.125 0 001.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375m0 0a1.125 1.125 0 01-1.125-1.125v-1.5c0-.621.504-1.125 1.125-1.125h17.25m-17.25 0a1.125 1.125 0 001.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H6.75m11.25 0h-4.5m4.5 0a1.125 1.125 0 011.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375" />
                </svg>
                <span x-show="sidebarOpen" class="ml-3 font-semibold whitespace-nowrap">Laporan Absensi</span>
            </a>
        </nav>

        {{-- Profile Section --}}
        <div class="px-3 py-4 border-t border-slate-700 flex-shrink-0"
             x-data="{ openProfile: false }"
             @click.outside="openProfile = false">
            <div class="relative">
                {{-- Trigger --}}
                <button @click="openProfile = !openProfile"
                    class="flex items-center w-full p-2 text-sm leading-4 font-medium rounded-xl
                           text-slate-300 hover:bg-slate-700 hover:text-white focus:outline-none
                           transition-colors duration-200">
                    {{-- Avatar --}}
                    <div class="flex-shrink-0 h-10 w-10 rounded-full bg-primary-yellow flex items-center justify-center">
                        <span class="text-lg font-bold text-dark-blue">
                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                        </span>
                    </div>
                    {{-- Username & Arrow --}}
                    <div x-show="sidebarOpen" class="flex items-center ml-3 whitespace-nowrap w-full">
                        <span class="truncate max-w-[120px]">{{ Auth::user()->name }}</span>
                        <svg class="ml-auto h-4 w-4 text-slate-400 transition-transform duration-200"
                             :class="{ 'rotate-180': openProfile }"
                             xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd"
                                  d="M5.23 7.21a.75.75 0 011.06.02L10 11.188l3.71-3.96a.75.75 0 111.1 1.02l-4.25 4.54a.75.75 0 01-1.1 0l-4.25-4.54a.75.75 0 01.02-1.06z"
                                  clip-rule="evenodd" />
                        </svg>
                    </div>
                </button>

                {{-- Dropdown Content --}}
                <div x-show="openProfile" x-transition
                     class="absolute bottom-full left-0 right-0 mb-2 bg-slate-700 rounded-lg shadow-lg ring-1 ring-black ring-opacity-5">
                    <div class="py-1">
                        <a href="{{ route('profile.edit') }}"
                           class="block px-4 py-2 text-sm text-slate-300 hover:bg-primary-yellow hover:text-dark-blue">
                            Profile
                        </a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit"
                                class="w-full text-left px-4 py-2 text-sm text-slate-300 hover:bg-primary-yellow hover:text-dark-blue">
                                Log Out
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </aside>
</div>
