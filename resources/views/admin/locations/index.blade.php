<x-app-layout>
    {{-- Memuat CSS Leaflet --}}
    @push('styles')
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
    @endpush

    {{-- Memanggil file JavaScript peta yang sudah di-compile oleh Vite --}}
    @vite('resources/js/admin-maps.js')

    <div class="space-y-6">
        <div class="bg-white p-6 rounded-lg shadow-md">
            <h2 class="text-xl font-bold text-dark-blue mb-4">Peta Lokasi Absensi</h2>
            <div id="map" class="h-80 w-full rounded-md z-0"></div>
            {{-- Div tersembunyi ini bertugas mengirim data lokasi dari PHP ke JavaScript --}}
            <div id="location-data" data-locations="{{ json_encode($locations->items()) }}" style="display: none;"></div>
        </div>

        <div class="bg-white p-6 rounded-lg shadow-md">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-bold text-dark-blue">Daftar Lokasi (Geofence)</h2>
                <a href="{{ route('admin.locations.create') }}" class="px-4 py-2 bg-dark-blue text-white rounded-lg shadow hover:bg-slate-700 transition">
                    + Tambah Geofence Baru
                </a>
            </div>

            @if (session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-md mb-4" role="alert">
                    <p>{{ session('success') }}</p>
                </div>
            @endif

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">#</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Pagar</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Deskripsi</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Radius (Meter)</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse ($locations as $location)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $loop->iteration }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $location->name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $location->address ?? 'N/A' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $location->radius }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                                    <a href="{{ route('admin.locations.edit', $location) }}" class="px-3 py-1 bg-yellow-500 text-white rounded-md hover:bg-yellow-600">Edit</a>
                                    <form action="{{ route('admin.locations.destroy', $location) }}" method="POST" class="inline-block" onsubmit="return confirm('Yakin ingin menghapus lokasi ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="px-3 py-1 bg-red-600 text-white rounded-md hover:bg-red-700">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="px-6 py-4 text-center text-gray-500">Belum ada data geofence.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
             <div class="mt-4">
                {{ $locations->links() }}
            </div>
        </div>
    </div>
</x-app-layout>