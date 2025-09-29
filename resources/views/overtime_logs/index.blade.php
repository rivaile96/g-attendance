<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Lembur') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-full">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">
                        Event Lembur Tersedia
                    </h3>

                    <div class="space-y-4">
                        @forelse ($availableEvents as $event)
                            <div class="p-4 border rounded-lg bg-indigo-50 border-indigo-200 flex flex-col sm:flex-row justify-between sm:items-center">
                                <div class="mb-3 sm:mb-0">
                                    <p class="font-semibold text-indigo-800">{{ $event->name }}</p>
                                    <p class="text-sm text-gray-600 mt-1">{{ $event->description }}</p>
                                    <p class="text-xs text-gray-500 mt-2">
                                        <i class="fa-solid fa-calendar-days mr-1"></i>
                                        Periode: {{ $event->start_date->translatedFormat('d M Y') }} - {{ $event->end_date->translatedFormat('d M Y') }}
                                        <span class="hidden sm:inline mx-2">|</span>
                                        <br class="sm:hidden">
                                        <i class="fa-solid fa-clock mr-1 mt-1 sm:mt-0"></i>
                                        Jam: {{ \Carbon\Carbon::parse($event->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($event->end_time)->format('H:i') }}
                                    </p>
                                </div>
                                
                                {{-- Logika untuk menampilkan/menyembunyikan tombol --}}
                                @if (!$claimedEventIds->contains($event->id))
                                    <a href="{{ route('overtime.create', $event) }}" class="inline-flex items-center justify-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-500 focus:bg-indigo-700 active:bg-indigo-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150 w-full sm:w-auto">
                                        Ajukan Klaim
                                    </a>
                                @else
                                    <button class="px-4 py-2 bg-gray-300 border border-transparent rounded-md font-semibold text-xs text-gray-600 uppercase tracking-widest cursor-not-allowed" disabled>
                                        Sudah Diajukan
                                    </button>
                                @endif
                            </div>
                        @empty
                            <div class="p-4 border border-dashed rounded-md text-center text-gray-500">
                                Tidak ada event lembur yang tersedia untuk divisi Anda saat ini.
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-full">
                    {{-- Tabel Riwayat --}}
                    {{-- ... (bagian ini tidak berubah) ... --}}
                </div>
            </div>

        </div>
    </div>
</x-app-layout>