<x-app-layout>
    {{-- Memuat CSS Leaflet --}}
    @push('styles')
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @endpush

    {{-- Memanggil file JavaScript peta yang sudah di-compile oleh Vite --}}
    @vite('resources/js/admin-maps.js')

    <div class="bg-white p-8 rounded-xl shadow-lg max-w-4xl mx-auto">
        <!-- Header -->
        <div class="flex items-center justify-between mb-8 pb-4 border-b">
            <div>
                <h1 class="text-3xl font-bold text-dark-blue">Tambah Lokasi Baru</h1>
                <p class="text-gray-600 mt-2">Buat area geofence baru untuk absensi</p>
            </div>
            <div class="bg-blue-50 p-3 rounded-lg">
                <i class="fas fa-map-marker-alt text-dark-blue text-2xl"></i>
            </div>
        </div>

        <!-- Form -->
        <form action="{{ route('admin.locations.store') }}" method="POST">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6">
                <!-- Kolom Kiri: Detail Lokasi -->
                <div class="space-y-6">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-1">
                            Nama Lokasi <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="name" id="name" value="{{ old('name') }}"
                            class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-dark-blue focus:border-dark-blue transition"
                            placeholder="Contoh: Kantor Pusat Jakarta" required>
                        @error('name') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label for="radius" class="block text-sm font-medium text-gray-700 mb-1">
                            Radius (meter) <span class="text-red-500">*</span>
                        </label>
                        <input type="number" name="radius" id="radius" value="{{ old('radius', 100) }}"
                            class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-dark-blue focus:border-dark-blue transition"
                            required>
                        @error('radius') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label for="address" class="block text-sm font-medium text-gray-700 mb-1">
                            Alamat / Deskripsi
                        </label>
                        <textarea name="address" id="address" rows="3"
                            class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-dark-blue focus:border-dark-blue transition"
                            placeholder="Deskripsi singkat lokasi">{{ old('address') }}</textarea>
                    </div>
                    <div>
                        <label for="ip_address" class="block text-sm font-medium text-gray-700 mb-1">
                            Alamat IP (Opsional)
                        </label>
                        <input type="text" name="ip_address" id="ip_address" value="{{ old('ip_address') }}"
                            class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-dark-blue focus:border-dark-blue transition"
                            placeholder="Untuk absensi via WiFi">
                        @error('ip_address') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                    </div>
                </div>

                <!-- Kolom Kanan: Peta -->
                <div class="space-y-2">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Koordinat Lokasi <span class="text-red-500">*</span>
                        </label>
                        
                        <!-- Tombol Fitur Baru -->
                        <button type="button" id="find-me-btn" class="mb-3 inline-flex items-center px-4 py-2 bg-dark-blue border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition ease-in-out duration-150">
                            <i class="fas fa-crosshairs mr-2"></i>
                            Temukan Lokasi Saya
                        </button>

                        <div class="flex space-x-2">
                            <input type="text" name="latitude" id="latitude" placeholder="Latitude" value="{{ old('latitude', '-6.2088') }}"
                                class="w-full px-4 py-2.5 rounded-lg border bg-gray-100 border-gray-300 cursor-not-allowed" required readonly>
                            <input type="text" name="longitude" id="longitude" placeholder="Longitude" value="{{ old('longitude', '106.8456') }}"
                                class="w-full px-4 py-2.5 rounded-lg border bg-gray-100 border-gray-300 cursor-not-allowed" required readonly>
                        </div>
                        @error('latitude') <span class="text-red-500 text-sm mt-1 d-block">{{ $message }}</span> @enderror
                        @error('longitude') <span class="text-red-500 text-sm mt-1 d-block">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <div id="map" class="h-64 w-full rounded-lg z-0"></div>
                        <p class="text-xs text-gray-500 mt-2">Klik atau geser marker di peta untuk menentukan lokasi.</p>
                    </div>
                </div>
            </div>

            <!-- Tombol Aksi -->
            <div class="mt-10 pt-6 border-t border-gray-200 flex flex-col sm:flex-row justify-end gap-3">
                <a href="{{ route('admin.locations.index') }}" 
                   class="px-6 py-3 bg-gray-100 text-gray-700 rounded-lg font-medium hover:bg-gray-200 transition flex items-center justify-center">
                    <i class="fas fa-times mr-2"></i> Batal
                </a>
                <button type="submit" 
                        class="px-6 py-3 bg-dark-blue text-white rounded-lg font-medium hover:bg-blue-800 transition flex items-center justify-center">
                    <i class="fas fa-save mr-2"></i> Simpan Lokasi
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
