<x-app-layout>
    @push('styles')
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
    @endpush

    {{-- Memanggil file JavaScript peta yang sudah di-compile oleh Vite --}}
    @vite('resources/js/admin-maps.js')

    <div class="bg-white p-8 rounded-lg shadow-md max-w-4xl mx-auto">
        <h1 class="text-2xl font-bold text-dark-blue mb-6">Edit Geofence: {{ $location->name }}</h1>
        
        <form action="{{ route('admin.locations.update', $location) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="space-y-4">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700">Geofence Name <span class="text-red-500">*</span></label>
                        <input type="text" name="name" id="name" value="{{ old('name', $location->name) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required>
                    </div>
                     <div>
                        <label for="radius" class="block text-sm font-medium text-gray-700">Radius (meters) <span class="text-red-500">*</span></label>
                        <input type="number" name="radius" id="radius" value="{{ old('radius', $location->radius) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required>
                    </div>
                    <div>
                        <label for="address" class="block text-sm font-medium text-gray-700">Description</label>
                        <textarea name="address" id="address" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">{{ old('address', $location->address) }}</textarea>
                    </div>
                     <div>
                        <label for="ip_address" class="block text-sm font-medium text-gray-700">IP Address (Optional)</label>
                        <input type="text" name="ip_address" id="ip_address" value="{{ old('ip_address', $location->ip_address) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    </div>
                </div>
                <div class="space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="latitude" class="block text-sm font-medium text-gray-700">Location Coordinates <span class="text-red-500">*</span></label>
                            <input type="text" name="latitude" id="latitude" placeholder="Latitude" value="{{ old('latitude', $location->latitude) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm sm:text-sm bg-gray-100" required readonly>
                        </div>
                        <div>
                            <label for="longitude" class="invisible block text-sm font-medium text-gray-700">Longitude</label>
                            <input type="text" name="longitude" id="longitude" placeholder="Longitude" value="{{ old('longitude', $location->longitude) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm sm:text-sm bg-gray-100" required readonly>
                        </div>
                    </div>
                    <div>
                        <div id="map" class="h-64 w-full rounded-md z-0"></div>
                        <p class="text-xs text-gray-500 mt-1">Click or drag marker to update location</p>
                    </div>
                </div>
            </div>
            <div class="mt-8 flex justify-end space-x-4">
                <a href="{{ route('admin.locations.index') }}" class="px-6 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300">Cancel</a>
                <button type="submit" class="px-6 py-2 bg-dark-blue text-white rounded-md hover:bg-slate-700">Update Geofence</button>
            </div>
        </form>
    </div>
</x-app-layout>