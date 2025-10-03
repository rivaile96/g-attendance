<x-app-layout>
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 py-8">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold text-dark-blue">Persetujuan Cuti & Izin</h1>
        </div>

        @if(session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-md mb-6" role="alert">
            {{ session('success') }}
        </div>
        @endif
        
        <div x-data="{ openId: null }" class="bg-white overflow-hidden shadow-md rounded-lg">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Karyawan</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tipe</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi / Info</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($leaves as $leave)
                    <tr @click="openId = (openId === {{ $leave->id }}) ? null : {{ $leave->id }}" class="hover:bg-gray-50 cursor-pointer">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $leave->user->name }}</div>
                            <div class="text-sm text-gray-500">{{ optional($leave->user->division)->name }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $leave->type }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $leave->start_date->format('d M') }} - {{ $leave->end_date->format('d M Y') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span @class([
                                'px-2 inline-flex text-xs leading-5 font-semibold rounded-full',
                                'bg-yellow-100 text-yellow-800' => $leave->status === 'Pending',
                                'bg-green-100 text-green-800' => $leave->status === 'Approved',
                                'bg-red-100 text-red-800' => $leave->status === 'Rejected',
                            ])>
                                {{ $leave->status }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            @if ($leave->status === 'Pending')
                                <div @click.stop class="flex items-center space-x-2">
                                    <button @click="showLeaveConfirmation({{ json_encode($leave) }}, 'Approved')" type="button" class="text-indigo-600 hover:text-indigo-900 focus:outline-none">Approve</button>
                                    <span class="text-gray-300">|</span>
                                    <button @click="showLeaveConfirmation({{ json_encode($leave) }}, 'Rejected')" type="button" class="text-red-600 hover:text-red-900 focus:outline-none">Reject</button>
                                </div>
                            @else
                                <span class="text-sm text-gray-500">Diproses oleh {{ optional($leave->approver)->name }}</span>
                            @endif
                        </td>
                    </tr>
                    
                    {{-- Baris baru untuk menampilkan detail alasan dan lampiran --}}
                    <tr x-show="openId === {{ $leave->id }}" x-collapse style="display: none;">
                        <td colspan="5" class="p-4 bg-blue-50 border-y border-blue-200">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                                <div>
                                    <strong class="block font-semibold text-dark-blue mb-2">Alasan / Keterangan:</strong>
                                    <p class="whitespace-pre-wrap pl-2 border-l-2 border-blue-300 text-gray-800">{{ $leave->reason ?: 'Tidak ada alasan.' }}</p>
                                </div>
                                <div>
                                    <strong class="block font-semibold text-dark-blue mb-2">Lampiran:</strong>
                                    @if($leave->attachment_path)
                                        <a href="{{ Storage::url($leave->attachment_path) }}" target="_blank" class="text-indigo-600 hover:underline">
                                            Lihat Lampiran <i class="fa-solid fa-external-link-alt ml-1"></i>
                                        </a>
                                    @else
                                        <span class="text-gray-500">Tidak ada lampiran.</span>
                                    @endif
                                </div>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="text-center py-6 text-gray-500">Belum ada pengajuan cuti & izin.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
         <div class="mt-4">
            {{ $leaves->links() }}
        </div>
    </div>

    <form id="leave-approval-form" method="POST" class="hidden">
        @csrf
        @method('PUT')
        <input type="hidden" name="status" id="leave-approval-status">
        <input type="hidden" name="rejection_reason" id="rejection_reason">
    </form>
</x-app-layout>