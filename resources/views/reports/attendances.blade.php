<x-app-layout>
    <div class="space-y-6">
        
        {{-- ▼▼▼ BAGIAN HEADER YANG DISEMPURNAKAN ▼▼▼ --}}
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center">
            <div>
                <h1 class="text-3xl font-bold text-dark-blue">Laporan Absensi</h1>
                <p class="text-gray-500 mt-1">Lihat dan kelola riwayat absensi.</p>
            </div>
            <a href="{{ route('reports.attendances.pdf', request()->query()) }}" class="mt-4 sm:mt-0 inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-500">
                <i class="fas fa-file-pdf mr-2"></i>
                Download PDF
            </a>
        </div>
        {{-- ▲▲▲ --------------------------------- ▲▲▲ --}}

        {{-- Filter Form (hanya tampil untuk admin) --}}
        @if (Auth::user()->is_admin)
        <div class="bg-white p-4 rounded-lg shadow-md">
            <form action="{{ route('reports.attendances') }}" method="GET">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <select name="user_id" class="rounded-md border-gray-300 shadow-sm">
                        <option value="">Semua Karyawan</option>
                        @foreach ($users as $user)
                            <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                        @endforeach
                    </select>
                    <input type="date" name="start_date" value="{{ request('start_date') }}" class="rounded-md border-gray-300 shadow-sm" title="Tanggal Mulai">
                    <input type="date" name="end_date" value="{{ request('end_date') }}" class="rounded-md border-gray-300 shadow-sm" title="Tanggal Selesai">
                    <div class="flex items-center space-x-2">
                        <button type="submit" class="w-full px-4 py-2 bg-primary-yellow text-dark-blue font-semibold rounded-lg shadow hover:opacity-80 transition">Filter</button>
                        <a href="{{ route('reports.attendances') }}" class="w-full px-4 py-2 text-center bg-gray-200 text-gray-700 rounded-lg shadow hover:bg-gray-300 transition">Reset</a>
                    </div>
                </div>
            </form>
        </div>
        @endif

        {{-- Tabel Riwayat (Script-mu yang sudah bagus tidak diubah) --}}
        <div class="bg-white overflow-hidden shadow-md rounded-lg">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            @if(Auth::user()->is_admin)
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Karyawan</th>
                            @endif
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jam Masuk</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jam Pulang</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Jam</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Lokasi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse ($attendances as $attendance)
                            <tr>
                                @if(Auth::user()->is_admin)
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">{{ $attendance->user->name }}</div>
                                        <div class="text-sm text-gray-500">{{ $attendance->user->division->name ?? '' }}</div>
                                    </td>
                                @endif
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $attendance->check_in->translatedFormat('d F Y') }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $attendance->check_in->format('H:i:s') }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    @if($attendance->check_out)
                                        {{ $attendance->check_out->format('H:i:s') }}
                                    @else
                                        <span class="text-red-500">Belum Check-out</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900">
                                    @if($attendance->check_out)
                                        {{ $attendance->check_in->diff($attendance->check_out)->format('%H jam %i menit') }}
                                    @else
                                        -
                                    @endif
                                </td>
                                 <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $attendance->location->name ?? 'N/A' }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="{{ Auth::user()->is_admin ? 6 : 5 }}" class="px-6 py-4 text-center text-gray-500">Tidak ada data absensi yang ditemukan.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="px-6 py-4">
                {{ $attendances->links() }}
            </div>
        </div>
    </div>
</x-app-layout>