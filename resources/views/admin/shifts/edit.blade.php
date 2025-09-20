<x-app-layout>
    <div class="bg-white p-8 rounded-xl shadow-lg max-w-2xl mx-auto">
        <div class="flex items-center justify-between mb-8 pb-4 border-b">
            <div>
                <h1 class="text-3xl font-bold text-dark-blue">Edit Jadwal Kerja</h1>
                <p class="text-gray-600 mt-2">Perbarui detail untuk shift: {{ $shift->name }}</p>
            </div>
            <div class="bg-blue-50 p-3 rounded-lg">
                <i class="fas fa-edit text-dark-blue text-2xl"></i>
            </div>
        </div>

        <form action="{{ route('admin.shifts.update', $shift) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="space-y-6">
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1">
                        Nama Shift <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="name" id="name" value="{{ old('name', $shift->name) }}"
                        class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-dark-blue focus:border-dark-blue transition"
                        required>
                    @error('name') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="start_time" class="block text-sm font-medium text-gray-700 mb-1">
                            Jam Masuk <span class="text-red-500">*</span>
                        </label>
                        <input type="time" name="start_time" id="start_time" value="{{ old('start_time', $shift->start_time) }}"
                            class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-dark-blue focus:border-dark-blue transition"
                            required>
                        @error('start_time') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label for="end_time" class="block text-sm font-medium text-gray-700 mb-1">
                            Jam Pulang <span class="text-red-500">*</span>
                        </label>
                        <input type="time" name="end_time" id="end_time" value="{{ old('end_time', $shift->end_time) }}"
                            class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-dark-blue focus:border-dark-blue transition"
                            required>
                        @error('end_time') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                    </div>
                </div>
            </div>

            <div class="mt-10 pt-6 border-t border-gray-200 flex flex-col sm:flex-row justify-end gap-3">
                <a href="{{ route('admin.shifts.index') }}" 
                   class="px-6 py-3 bg-gray-100 text-gray-700 rounded-lg font-medium hover:bg-gray-200 transition flex items-center justify-center">
                    Batal
                </a>
                <button type="submit" 
                        class="px-6 py-3 bg-dark-blue text-white rounded-lg font-medium hover:bg-blue-800 transition flex items-center justify-center">
                    Perbarui Jadwal Kerja
                </button>
            </div>
        </form>
    </div>
</x-app-layout>