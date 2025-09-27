<x-app-layout>
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <h1 class="text-3xl font-bold text-dark-blue mb-6">Kalender Perusahaan</h1>

        @if (session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-md mb-6" role="alert">
                <p>{{ session('success') }}</p>
            </div>
        @endif
        
        <div class="bg-white p-4 sm:p-6 rounded-xl shadow-md">
            <div id='calendar-admin' class="min-h-[600px]"></div>
            <p class="text-sm text-center text-gray-500 mt-2">
                <i class="fas fa-info-circle mr-1"></i>
                **Tips:** Klik tanggal untuk menambah hari libur, atau klik event libur untuk menghapusnya.
            </p>
        </div>
    </div>
</x-app-layout>