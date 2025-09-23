<x-app-layout>
    {{-- Memuat CSS FullCalendar dari CDN --}}
    @push('styles')
        <link href='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css' rel='stylesheet' />
    @endpush

    <div>
        <h1 class="text-3xl font-bold text-dark-blue">Selamat Datang, {{ Auth::user()->name }}!</h1>
        <p class="text-gray-500">Berikut adalah ringkasan aktivitas absensi hari ini.</p>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mt-6">

            <div class="col-span-1 gsap-widget">
                <x-stat-card title="Total Karyawan" :value="$totalEmployees" iconColor="bg-dark-blue">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                </x-stat-card>
            </div>

            <div class="col-span-1 gsap-widget">
                <x-stat-card title="Hadir Hari Ini" :value="$presentToday" iconColor="bg-green-500">
                     <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </x-stat-card>
            </div>

            <div class="col-span-1 gsap-widget">
                 <x-stat-card title="Terlambat Hari Ini" :value="$lateToday" iconColor="bg-primary-yellow">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </x-stat-card>
            </div>

            <div class="col-span-1 gsap-widget">
                <x-stat-card title="Belum Absen" :value="$absentToday" iconColor="bg-red-500">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"></path></svg>
                </x-stat-card>
            </div>
            
            <div class="col-span-1 md:col-span-2 lg:col-span-4 mt-2 gsap-widget">
                <div class="h-full bg-white p-6 rounded-lg shadow-md flex flex-col">
                    <h2 class="text-xl font-bold text-dark-blue mb-4">Grafik Kehadiran 7 Hari Terakhir</h2>
                    <div id="attendanceChart" class="flex-grow min-h-[300px]"></div>
                </div>
            </div>

            <div class="col-span-1 md:col-span-2 lg:col-span-4 mt-2 gsap-widget">
                <div class="h-full bg-white p-6 rounded-lg shadow-md flex flex-col">
                    <h2 class="text-xl font-bold text-dark-blue mb-4">Kalender Perusahaan</h2>
                    <div id="calendar-dashboard" class="flex-grow min-h-[400px]"></div>
                </div>
            </div>

        </div>
    </div>

    @push('scripts')
<script>
    // Melempar data dari PHP ke JavaScript global
    window.dashboardChartData = {
        labels: @json($chartLabels),
        data: @json($chartData)
    };
</script>
@endpush
</x-app-layout>