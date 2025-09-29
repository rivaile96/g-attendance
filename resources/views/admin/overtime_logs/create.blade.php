<x-app-layout>
    <div class="bg-white p-8 rounded-xl shadow-lg max-w-2xl mx-auto">
        <h1 class="text-3xl font-bold text-dark-blue mb-6">Ajukan Klaim Lembur</h1>
        <form action="{{ route('overtime.store') }}" method="POST">
            @csrf
            <div class="space-y-6">
                <div>
                    <label for="overtime_event_id">Pilih Event Lembur</label>
                    <select name="overtime_event_id" id="overtime_event_id" class="mt-1 w-full rounded-md" required>
                        <option value="">-- Pilih Event --</option>
                        @foreach($availableEvents as $event)
                        <option value="{{ $event->id }}">{{ $event->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="date">Tanggal Lembur</label>
                    <input type="date" name="date" id="date" value="{{ now()->format('Y-m-d') }}" class="mt-1 w-full rounded-md" required>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="start_time">Jam Mulai</label>
                        <input type="time" name="start_time" id="start_time" class="mt-1 w-full rounded-md" required>
                    </div>
                    <div>
                        <label for="end_time">Jam Selesai</label>
                        <input type="time" name="end_time" id="end_time" class="mt-1 w-full rounded-md" required>
                    </div>
                </div>
                <div>
                    <label for="notes">Catatan Pekerjaan</label>
                    <textarea name="notes" id="notes" rows="4" class="mt-1 w-full rounded-md" required></textarea>
                </div>
                <div class="flex justify-end gap-3">
                    <a href="{{ route('overtime.index') }}" class="px-6 py-2 bg-gray-200 rounded-md">Batal</a>
                    <button type="submit" class="px-6 py-2 bg-dark-blue text-white rounded-md">Ajukan</button>
                </div>
            </div>
        </form>
    </div>
</x-app-layout>