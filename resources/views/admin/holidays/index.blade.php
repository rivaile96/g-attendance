<x-app-layout>
    {{-- 
        Alpine.js x-data untuk mengontrol modal tambah hari libur.
        Logika untuk 'menghidupkan' kalender di bawah ini ada di resources/js/app.js 
    --}}
    <div x-data="{
        showModal: false,
        modalAction: '',
        modalDate: '',
        modalDescription: '',
        submitForm() {
            const form = document.getElementById('holidayForm');
            form.action = this.modalAction;
            form.querySelector('#date').value = this.modalDate;
            form.querySelector('#description').value = this.modalDescription;
            form.submit();
        }
    }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <h1 class="text-3xl font-bold text-dark-blue mb-6">Kalender Perusahaan</h1>

            @if (session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-md mb-6" role="alert">
                    <p>{{ session('success') }}</p>
                </div>
            @endif
             @if ($errors->any())
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded-lg mb-6" role="alert">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            
            <div class="bg-white p-4 sm:p-6 rounded-xl shadow-md">
                {{-- Kalender akan dirender di dalam div ini oleh app.js --}}
                <div id='calendar-admin' class="min-h-[600px]"></div>
            </div>
        </div>

        <div x-show="showModal" class="fixed z-50 inset-0 overflow-y-auto" x-cloak>
            <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center">
                <div class="fixed inset-0 transition-opacity" @click="showModal = false" aria-hidden="true">
                    <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
                </div>
                <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <form id="holidayForm" method="POST" @submit.prevent="submitForm">
                        @csrf
                        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                            <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">Tambah Hari Libur Baru</h3>
                            <div class="mt-4 space-y-4">
                                <div>
                                    <label for="date" class="block text-sm font-medium text-gray-700">Tanggal</label>
                                    <input type="date" id="date" name="date" x-model="modalDate" class="mt-1 w-full rounded-md border-gray-300 bg-gray-100 cursor-not-allowed" readonly>
                                </div>
                                <div>
                                    <label for="description" class="block text-sm font-medium text-gray-700">Keterangan</label>
                                    <input type="text" id="description" name="description" x-model="modalDescription" class="mt-1 w-full rounded-md border-gray-300 focus:ring-dark-blue focus:border-dark-blue" placeholder="Cth: Cuti Bersama Idul Fitri" required>
                                </div>
                            </div>
                        </div>
                        <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                            <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-dark-blue text-base font-medium text-white hover:bg-blue-800 sm:ml-3 sm:w-auto sm:text-sm">Simpan</button>
                            <button type="button" @click="showModal = false" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 sm:mt-0 sm:w-auto sm:text-sm">Batal</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    {{-- TIDAK ADA @push('scripts') DI SINI, KARENA SUDAH DIHANDLE OLEH app.js --}}
</x-app-layout>