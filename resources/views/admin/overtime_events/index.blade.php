<x-app-layout>
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold text-dark-blue">Manajemen Event Lembur</h1>
            <a href="{{ route('admin.overtime-events.create') }}" class="px-4 py-2 bg-dark-blue text-white rounded-lg">+ Buat Event Baru</a>
        </div>

        @if(session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-md mb-6">
            {{ session('success') }}
        </div>
        @endif
        
        <div class="bg-white overflow-hidden shadow-md rounded-lg">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama Event</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Periode</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Jam</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Untuk Divisi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($overtimeEvents as $event)
                    <tr>
                        <td class="px-6 py-4">{{ $event->name }}</td>
                        <td class="px-6 py-4">{{ $event->start_date->format('d M') }} - {{ $event->end_date->format('d M Y') }}</td>
                        <td class="px-6 py-4">{{ \Carbon\Carbon::parse($event->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($event->end_time)->format('H:i') }}</td>
                        <td class="px-6 py-4">
                            @foreach($event->assignments as $assignment)
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                    {{ $assignment->assignable->name }}
                                </span>
                            @endforeach
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="4" class="text-center py-4">Belum ada event lembur yang dibuat.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>