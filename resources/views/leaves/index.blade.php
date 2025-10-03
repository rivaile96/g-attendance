<x-app-layout>
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 py-8">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold text-dark-blue">Riwayat Cuti & Izin</h1>
            <a href="{{ route('leaves.create') }}" class="px-4 py-2 bg-dark-blue text-white rounded-lg shadow-md hover:bg-opacity-90 transition">
                <i class="fa-solid fa-plus mr-2"></i> Ajukan Cuti Baru
            </a>
        </div>

        @if(session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-md mb-6" role="alert">
            {{ session('success') }}
        </div>
        @endif
        
        <div x-data="{ openId: null }" class="bg-white overflow-hidden shadow-md rounded-lg">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal Pengajuan</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tipe</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rentang Tanggal</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse ($leaves as $leave)
                        {{-- 1. Tambahkan event klik hanya jika status 'Rejected' --}}
                        <tr 
                            @if($leave->status === 'Rejected')
                                @click="openId = (openId === {{ $leave->id }}) ? null : {{ $leave->id }}"
                                class="hover:bg-gray-50 cursor-pointer"
                            @endif
                        >
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                {{ $leave->created_at->translatedFormat('d F Y') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 font-medium">{{ $leave->type }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                {{ $leave->start_date->format('d M') }} - {{ $leave->end_date->format('d M Y') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span @class([
                                    'px-3 py-1 inline-flex text-xs leading-5 font-bold rounded-full',
                                    'bg-yellow-100 text-yellow-800' => $leave->status === 'Pending',
                                    'bg-green-100 text-green-800' => $leave->status === 'Approved',
                                    'bg-red-100 text-red-800' => $leave->status === 'Rejected',
                                ])>
                                    {{ $leave->status }}
                                    @if($leave->status === 'Rejected')
                                        <i class="fa-solid fa-chevron-down ml-2"></i>
                                    @endif
                                </span>
                            </td>
                        </tr>
                        
                        {{-- 2. Baris ini hanya akan muncul jika ada alasan penolakan --}}
                        @if ($leave->status === 'Rejected')
                        <tr x-show="openId === {{ $leave->id }}" x-collapse style="display: none;">
                            <td colspan="4" class="p-4 bg-red-50 border-y border-red-200">
                                <div class="text-sm text-red-900">
                                    <strong class="block font-semibold mb-2">Alasan Penolakan:</strong>
                                    <p class="whitespace-pre-wrap pl-2 border-l-2 border-red-300">
                                        {{ $leave->rejection_reason ?: 'Tidak ada alasan spesifik yang diberikan.' }}
                                    </p>
                                </div>
                            </td>
                        </tr>
                        @endif
                        @empty
                        <tr><td colspan="4" class="text-center py-6 text-gray-500">Anda belum pernah mengajukan cuti atau izin.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
         <div class="mt-4">
            {{ $leaves->links() }}
        </div>
    </div>
</x-app-layout>