<x-app-layout>
    <div class="bg-white p-8 rounded-xl shadow-lg max-w-4xl mx-auto">
        <h1 class="text-3xl font-bold text-dark-blue mb-6">Buat Event Lembur Baru</h1>
        <form action="{{ route('admin.overtime-events.store') }}" method="POST">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- Kolom Kiri --}}
                <div class="space-y-4">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700">Nama Event Lembur</label>
                        <input type="text" name="name" id="name" class="mt-1 w-full rounded-md" required>
                    </div>
                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700">Deskripsi (Opsional)</label>
                        <textarea name="description" id="description" rows="3" class="mt-1 w-full rounded-md"></textarea>
                    </div>
                    <div>
                        <label for="division_ids" class="block text-sm font-medium text-gray-700">Tugaskan ke Divisi</label>
                        <select name="division_ids[]" id="division_ids" multiple class="mt-1 w-full rounded-md">
                            @foreach ($divisions as $division)
                                <option value="{{ $division->id }}">{{ $division->name }}</option>
                            @endforeach
                        </select>
                        <p class="text-xs text-gray-500 mt-1">Tahan Ctrl (atau Cmd di Mac) untuk memilih lebih dari satu.</p>
                    </div>
                </div>
                {{-- Kolom Kanan --}}
                <div class="space-y-4">
                     <div>
                        <label for="start_date" class="block text-sm font-medium text-gray-700">Tanggal Mulai</label>
                        <input type="date" name="start_date" id="start_date" class="mt-1 w-full rounded-md" required>
                    </div>
                    <div>
                        <label for="end_date" class="block text-sm font-medium text-gray-700">Tanggal Selesai</label>
                        <input type="date" name="end_date" id="end_date" class="mt-1 w-full rounded-md" required>
                    </div>
                     <div>
                        <label for="start_time" class="block text-sm font-medium text-gray-700">Jam Mulai</label>
                        <input type="time" name="start_time" id="start_time" class="mt-1 w-full rounded-md" required>
                    </div>
                    <div>
                        <label for="end_time" class="block text-sm font-medium text-gray-700">Jam Selesai</label>
                        <input type="time" name="end_time" id="end_time" class="mt-1 w-full rounded-md" required>
                    </div>
                </div>
            </div>
            <div class="mt-8 flex justify-end gap-3">
                <a href="{{ route('admin.overtime-events.index') }}" class="px-6 py-2 bg-gray-200 rounded-md">Batal</a>
                <button type="submit" class="px-6 py-2 bg-dark-blue text-white rounded-md">Simpan Event</button>
            </div>
        </form>
    </div>
</x-app-layout>