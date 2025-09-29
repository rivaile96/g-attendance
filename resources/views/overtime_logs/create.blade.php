<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Form Pengajuan Klaim Lembur') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 md:p-8 text-gray-900">

                    <div class="mb-6 p-4 border rounded-lg bg-gray-50">
                        <h3 class="font-bold text-lg">Anda Mengajukan Klaim Untuk Event:</h3>
                        <p class="font-semibold text-indigo-700 text-xl mt-1">{{ $event->name }}</p>
                        <p class="text-sm text-gray-600 mt-1">{{ $event->description }}</p>
                    </div>
                    
                    {{-- Tampilkan pesan error jika ada --}}
                    @if (session('error'))
                        <div class="mb-4 p-4 bg-red-100 text-red-700 border border-red-200 rounded-md">
                            {{ session('error') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('overtime.store') }}">
                        @csrf
                        <input type="hidden" name="overtime_event_id" value="{{ $event->id }}">

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <x-input-label for="date" :value="__('Tanggal Lembur')" />
                                <x-text-input id="date" class="block mt-1 w-full bg-gray-100 cursor-not-allowed" type="date" name="date" :value="now()->toDateString()" readonly />
                                <x-input-error :messages="$errors->get('date')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="start_time" :value="__('Jam Mulai Sesuai Jadwal')" />
                                <x-text-input id="start_time" class="block mt-1 w-full bg-gray-100 cursor-not-allowed" type="time" name="start_time" :value="\Carbon\Carbon::parse($event->start_time)->format('H:i')" readonly />
                                <x-input-error :messages="$errors->get('start_time')" class="mt-2" />
                            </div>

                            <div class="md:col-span-2">
                                <x-input-label for="end_time" :value="__('Jam Selesai Aktual Anda')" />
                                <x-text-input id="end_time" class="block mt-1 w-full" type="time" name="end_time" :value="old('end_time')" required />
                                <x-input-error :messages="$errors->get('end_time')" class="mt-2" />
                            </div>
                        </div>

                        <div class="mt-6">
                            <x-input-label for="notes" :value="__('Catatan Pekerjaan (Deskripsikan apa yang Anda kerjakan)')" />
                            <textarea id="notes" name="notes" rows="4" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">{{ old('notes') }}</textarea>
                            <x-input-error :messages="$errors->get('notes')" class="mt-2" />
                        </div>
                        
                        <div class="flex items-center justify-end mt-6 gap-4">
                            <a href="{{ route('overtime.index') }}" class="text-sm text-gray-600 hover:text-gray-900">
                                Batal
                            </a>
                            <x-primary-button>
                                {{ __('Simpan Klaim') }}
                            </x-primary-button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>