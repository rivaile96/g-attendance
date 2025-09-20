<x-app-layout>
    <div class="bg-white p-8 rounded-xl shadow-lg max-w-2xl mx-auto">
        <div class="flex items-center justify-between mb-8 pb-4 border-b">
            <div>
                <h1 class="text-3xl font-bold text-dark-blue">Pengajuan Cuti & Izin</h1>
                <p class="text-gray-600 mt-2">Isi form di bawah untuk mengajukan permohonan</p>
            </div>
            <div class="bg-blue-50 p-3 rounded-lg">
                <i class="fas fa-calendar-alt text-dark-blue text-2xl"></i>
            </div>
        </div>

        <form action="{{ route('leaves.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="space-y-6">
                
                <div>
                    <label for="type" class="block text-sm font-medium text-gray-700 mb-1">
                        Tipe Pengajuan <span class="text-red-500">*</span>
                    </label>
                    <select name="type" id="type" required
                            class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-dark-blue focus:border-dark-blue transition">
                        <option value="Cuti Tahunan" {{ old('type') == 'Cuti Tahunan' ? 'selected' : '' }}>Cuti Tahunan</option>
                        <option value="Sakit" {{ old('type') == 'Sakit' ? 'selected' : '' }}>Sakit</option>
                        <option value="Izin Khusus" {{ old('type') == 'Izin Khusus' ? 'selected' : '' }}>Izin Khusus</option>
                    </select>
                    @error('type') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="start_date" class="block text-sm font-medium text-gray-700 mb-1">
                            Tanggal Mulai <span class="text-red-500">*</span>
                        </label>
                        <input type="date" name="start_date" id="start_date" value="{{ old('start_date') }}"
                            class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-dark-blue focus:border-dark-blue transition"
                            required>
                        @error('start_date') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label for="end_date" class="block text-sm font-medium text-gray-700 mb-1">
                            Tanggal Selesai <span class="text-red-500">*</span>
                        </label>
                        <input type="date" name="end_date" id="end_date" value="{{ old('end_date') }}"
                            class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-dark-blue focus:border-dark-blue transition"
                            required>
                        @error('end_date') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div>
                    <label for="reason" class="block text-sm font-medium text-gray-700 mb-1">
                        Alasan / Keterangan <span class="text-red-500">*</span>
                    </label>
                    <textarea name="reason" id="reason" rows="4" required
                        class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-dark-blue focus:border-dark-blue transition"
                        placeholder="Jelaskan alasan pengajuan cuti atau izin Anda di sini...">{{ old('reason') }}</textarea>
                    @error('reason') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label for="attachment" class="block text-sm font-medium text-gray-700 mb-1">
                        Lampiran (Opsional)
                    </label>
                    <input type="file" name="attachment" id="attachment" 
                        class="block w-full text-sm text-slate-500
                               file:mr-4 file:py-2 file:px-4
                               file:rounded-lg file:border-0
                               file:text-sm file:font-semibold
                               file:bg-blue-50 file:text-dark-blue
                               hover:file:bg-blue-100">
                    <p class="text-xs text-gray-500 mt-1">Maks: 2MB. Format: JPG, PNG, PDF. (Contoh: Surat Dokter)</p>
                    @error('attachment') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="mt-10 pt-6 border-t border-gray-200 flex flex-col sm:flex-row justify-end gap-3">
                <a href="{{ route('leaves.index') }}" class="px-6 py-3 bg-gray-100 text-gray-700 rounded-lg font-medium hover:bg-gray-200 transition flex items-center justify-center">
                    Batal
                </a>
                <button type="submit" class="px-6 py-3 bg-dark-blue text-white rounded-lg font-medium hover:bg-blue-800 transition flex items-center justify-center">
                    Kirim Pengajuan
                </button>
            </div>
        </form>
    </div>
</x-app-layout>