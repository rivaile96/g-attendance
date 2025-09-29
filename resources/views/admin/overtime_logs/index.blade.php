<x-app-layout>
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
        <div class="flex justify-between items-center">
            <h1 class="text-3xl font-bold text-dark-blue">Lembur</h1>
            <a href="{{ route('overtime.create') }}" class="px-4 py-2 bg-dark-blue text-white rounded-lg">+ Ajukan Klaim Lembur</a>
        </div>
        
        @if(session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-md">
            {{ session('success') }}
        </div>
        @endif

        <div class="bg-white p-6 rounded-xl shadow-md">
            <h2 class="text-xl font-bold text-dark-blue mb-4">Event Lembur Aktif Hari Ini</h2>
            @forelse($availableEvents as $event)
                <div class="border-l-4 border-primary-yellow pl-4 py-2 mb-2">
                    <p class="font-semibold">{{ $event->name }}</p>
                    <p class="text-sm text-gray-600">{{ $event->description }}</p>
                    <p class="text-xs text-gray-500">Jadwal: {{ \Carbon\Carbon::parse($event->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($event->end_time)->format('H:i') }}</p>
                </div>
            @empty
                <p class="text-gray-500">Tidak ada event lembur yang ditugaskan untukmu hari ini.</p>
            @endforelse
        </div>

        <div class="bg-white overflow-hidden shadow-md rounded-lg">
            <h2 class="text-xl font-bold text-dark-blue p-6">Riwayat Pengajuan Lembur</h2>
            <table class="min-w-full divide-y divide-gray-200">
                {{-- ... isi tabel riwayat ... --}}
            </table>
        </div>
    </div>
</x-app-layout>