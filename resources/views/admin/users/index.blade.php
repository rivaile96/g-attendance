<x-app-layout>
    <div class="space-y-6">
        {{-- Header Halaman --}}
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center">
            <div>
                <h1 class="text-3xl font-bold text-dark-blue">Manajemen Karyawan</h1>
                <p class="text-gray-500 mt-1">Kelola semua akun karyawan yang terdaftar.</p>
            </div>
            <a href="{{ route('admin.users.create') }}" class="mt-4 md:mt-0 px-4 py-2 bg-dark-blue text-white rounded-lg shadow hover:bg-slate-700 transition w-full md:w-auto text-center">
                + Tambah Karyawan
            </a>
        </div>
        
        {{-- Notifikasi --}}
        @if (session('success')) <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-md" role="alert"><p>{{ session('success') }}</p></div> @endif
        @if (session('error')) <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded-md" role="alert"><p>{{ session('error') }}</p></div> @endif
        
        <!-- ========================================================== -->
        <!-- BAGIAN BARU: FORM FILTER PENCARIAN -->
        <!-- ========================================================== -->
        <div class="bg-white p-4 rounded-lg shadow-md">
            <form action="{{ route('admin.users.index') }}" method="GET">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    {{-- Filter by Name --}}
                    <input type="text" name="name" placeholder="Cari Nama Karyawan..." value="{{ request('name') }}" class="rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                    
                    {{-- Filter by Division --}}
                    <select name="division_id" class="rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        <option value="">Semua Divisi</option>
                        @foreach ($divisions as $division)
                            <option value="{{ $division->id }}" {{ request('division_id') == $division->id ? 'selected' : '' }}>{{ $division->name }}</option>
                        @endforeach
                    </select>
                    
                    {{-- Filter by Join Date --}}
                    <input type="date" name="join_date" value="{{ request('join_date') }}" class="rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                    
                    {{-- Action Buttons --}}
                    <div class="flex items-center space-x-2">
                        <button type="submit" class="w-full px-4 py-2 bg-primary-yellow text-dark-blue font-semibold rounded-lg shadow hover:opacity-80 transition">Filter</button>
                        <a href="{{ route('admin.users.index') }}" class="w-full px-4 py-2 text-center bg-gray-200 text-gray-700 rounded-lg shadow hover:bg-gray-300 transition">Reset</a>
                    </div>
                </div>
            </form>
        </div>

        {{-- Container Tabel & Kartu --}}
        <div class="bg-white overflow-hidden shadow-md rounded-lg">
            {{-- Tampilan Tabel untuk Desktop --}}
            <div class="hidden md:block">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Karyawan</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Divisi</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal Bergabung</th>
                                <th scope="col" class="relative px-6 py-3"><span class="sr-only">Aksi</span></th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse ($users as $user)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10">
                                                <img class="h-10 w-10 rounded-full object-cover" src="{{ $user->profile_photo_path ? asset('storage/' . $user->profile_photo_path) : 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&background=random' }}" alt="{{ $user->name }}">
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900">{{ $user->name }}</div>
                                                <div class="text-sm text-gray-500">{{ $user->email }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        {{-- PERUBAHAN: Label Warna Dinamis --}}
                                        @if ($user->division)
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $user->division->color_class ?? 'bg-gray-100 text-gray-800' }}">
                                                {{ $user->division->name }}
                                            </span>
                                        @else
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">Belum Diatur</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $user->employmentData?->join_date?->translatedFormat('d F Y') ?? 'N/A' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-2">
                                        <a href="{{ route('admin.users.edit', $user) }}" class="px-3 py-1 bg-yellow-500 text-white rounded-md hover:bg-yellow-600">Edit</a>
                                        <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="inline-block" onsubmit="return confirm('Yakin ingin menghapus karyawan ini?');">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="px-3 py-1 bg-red-600 text-white rounded-md hover:bg-red-700">Hapus</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="4" class="px-6 py-4 text-center text-gray-500">Data karyawan tidak ditemukan.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Tampilan Kartu untuk Mobile --}}
            <div class="block md:hidden">
                <div class="px-4 py-4 space-y-4">
                    @forelse ($users as $user)
                        <div class="bg-white p-4 rounded-lg shadow ring-1 ring-gray-900/5">
                            <div class="flex items-center space-x-3">
                                <img class="h-12 w-12 rounded-full object-cover" src="{{ $user->profile_photo_path ? asset('storage/' . $user->profile_photo_path) : 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&background=random' }}" alt="{{ $user->name }}">
                                <div class="flex-1">
                                    <p class="font-semibold text-gray-900 truncate">{{ $user->name }}</p>
                                    <p class="text-sm text-gray-500 truncate">{{ $user->email }}</p>
                                </div>
                            </div>
                            <div class="mt-3 border-t border-gray-200 pt-3">
                                <dl class="grid grid-cols-2 gap-x-4 gap-y-2">
                                    <div class="col-span-2">
                                        <dt class="text-xs font-medium text-gray-500">Divisi</dt>
                                        <dd class="mt-1">
                                            {{-- PERUBAHAN: Label Warna Dinamis --}}
                                            @if ($user->division)
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $user->division->color_class ?? 'bg-gray-100 text-gray-800' }}">
                                                    {{ $user->division->name }}
                                                </span>
                                            @else
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">Belum Diatur</span>
                                            @endif
                                        </dd>
                                    </div>
                                    <div class="col-span-2">
                                        <dt class="text-xs font-medium text-gray-500">Tanggal Bergabung</dt>
                                        <dd class="text-sm text-gray-900">{{ $user->employmentData?->join_date?->translatedFormat('d F Y') ?? 'N/A' }}</dd>
                                    </div>
                                </dl>
                            </div>
                            <div class="mt-3 border-t border-gray-200 pt-3 flex justify-end space-x-2">
                                <a href="{{ route('admin.users.edit', $user) }}" class="px-3 py-1 text-sm bg-yellow-500 text-white rounded-md hover:bg-yellow-600">Edit</a>
                                <form action="{{ route('admin.users.destroy', $user) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus karyawan ini?');">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="px-3 py-1 text-sm bg-red-600 text-white rounded-md hover:bg-red-700">Hapus</button>
                                </form>
                            </div>
                        </div>
                    @empty
                        <p class="text-center text-gray-500">Data karyawan tidak ditemukan.</p>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- Paginasi --}}
        <div class="px-6 py-4">
            {{ $users->links() }}
        </div>
    </div>
</x-app-layout>

