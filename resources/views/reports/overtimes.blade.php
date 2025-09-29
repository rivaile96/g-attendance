<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Laporan Lembur Karyawan') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    {{-- FORM FILTER --}}
                    <form method="GET" action="{{ route('reports.overtimes') }}" class="mb-6">
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
                            <div>
                                <label for="start_date" class="block text-sm font-medium text-gray-700">Tanggal Mulai</label>
                                <input type="date" name="start_date" id="start_date" value="{{ request('start_date') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            </div>
                            <div>
                                <label for="end_date" class="block text-sm font-medium text-gray-700">Tanggal Selesai</label>
                                <input type="date" name="end_date" id="end_date" value="{{ request('end_date') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            </div>
                            <div>
                                <label for="user_id" class="block text-sm font-medium text-gray-700">Karyawan</label>
                                <select name="user_id" id="user_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                    <option value="">Semua Karyawan</option>
                                    @foreach($users as $user)
                                        <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                                            {{ $user->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="flex items-center space-x-2">
                                <button type="submit" class="w-full justify-center inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">Filter</button>
                                <a href="{{ route('reports.overtimes') }}" class="w-full justify-center inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-50">Reset</a>
                            </div>
                        </div>
                    </form>

                    {{-- TOMBOL EXPORT --}}
                    @if($overtimeLogs->count() > 0)
                    <div class="mb-4 flex items-center space-x-2">
                        <p class="text-sm font-medium text-gray-700">Cetak Laporan:</p>
                        <a href="{{ route('reports.overtimes.export', array_merge(request()->query(), ['type' => 'pdf'])) }}" class="inline-flex items-center px-3 py-1.5 bg-red-600 text-white text-xs font-medium rounded-md hover:bg-red-700">
                            <i class="fa-solid fa-file-pdf mr-2"></i>PDF
                        </a>
                        <a href="{{ route('reports.overtimes.export', array_merge(request()->query(), ['type' => 'xlsx'])) }}" class="inline-flex items-center px-3 py-1.5 bg-green-600 text-white text-xs font-medium rounded-md hover:bg-green-700">
                            <i class="fa-solid fa-file-excel mr-2"></i>Excel
                        </a>
                    </div>
                    @endif

                    {{-- TABEL DATA --}}
                    <div class="overflow-x-auto border rounded-lg">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Karyawan</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jam</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Durasi</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Event</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @php $totalMinutes = 0; @endphp
                                @forelse ($overtimeLogs as $log)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $log->user->name }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $log->start_time->translatedFormat('d M Y') }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $log->start_time->format('H:i') }} - {{ $log->end_time->format('H:i') }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            @php
                                                $duration = $log->start_time->diffInMinutes($log->end_time);
                                                $totalMinutes += $duration;
                                                echo floor($duration / 60) . ' jam ' . ($duration % 60) . ' mnt';
                                            @endphp
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ optional($log->overtimeEvent)->name }}</td>
                                    </tr>
                                @empty
                                    <tr><td colspan="5" class="text-center py-8 text-gray-500">Tidak ada data lembur yang disetujui untuk filter ini.</td></tr>
                                @endforelse
                            </tbody>
                             @if($overtimeLogs->count() > 0)
                                <tfoot class="bg-gray-50">
                                    <tr>
                                        <th colspan="3" class="px-6 py-3 text-right text-sm font-medium text-gray-700 uppercase">Total Durasi</th>
                                        <th class="px-6 py-3 text-left text-sm font-bold text-gray-900">
                                            {{ floor($totalMinutes / 60) . ' jam ' . ($totalMinutes % 60) . ' menit' }}
                                        </th>
                                        <th></th>
                                    </tr>
                                </tfoot>
                            @endif
                        </table>
                    </div>

                    <div class="mt-4">{{ $overtimeLogs->links() }}</div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>