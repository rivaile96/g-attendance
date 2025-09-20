<x-app-layout>
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8" x-data="{
        showRejectModal: false,
        showApproveModal: false,
        selectedLeave: null,
        rejectionReason: '',
        formAction: ''
    }">

        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-3xl font-bold text-dark-blue">Persetujuan Cuti & Izin</h1>
                <p class="text-gray-500 mt-1">Review dan proses pengajuan dari karyawan.</p>
            </div>
        </div>

        @if (session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-md mb-6" role="alert">
                <p>{{ session('success') }}</p>
            </div>
        @endif

        <div class="bg-white overflow-hidden shadow-md rounded-lg">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Karyawan</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tipe</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse ($leaves as $leave)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">{{ $leave->user->name }}</div>
                                    <div class="text-sm text-gray-500">{{ $leave->user->division->name ?? 'N/A' }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $leave->type }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $leave->start_date->format('d M') }} - {{ $leave->end_date->format('d M Y') }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @php
                                        $statusClass = [
                                            'Pending' => 'bg-yellow-100 text-yellow-800',
                                            'Approved' => 'bg-green-100 text-green-800',
                                            'Rejected' => 'bg-red-100 text-red-800',
                                        ][$leave->status] ?? 'bg-gray-100 text-gray-800';
                                    @endphp
                                    <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusClass }}">
                                        {{ $leave->status }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    @if ($leave->status === 'Pending')
                                        <button @click="showApproveModal = true; selectedLeave = {{ $leave }}; formAction = '{{ route('admin.leaves.update', $leave) }}'" class="text-green-600 hover:text-green-900">Approve</button>
                                        <button @click="showRejectModal = true; selectedLeave = {{ $leave }}; formAction = '{{ route('admin.leaves.update', $leave) }}'" class="text-red-600 hover:text-red-900 ml-4">Reject</button>
                                    @else
                                        <span class="text-gray-400">Diproses</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="text-center py-4 text-gray-500">Tidak ada pengajuan cuti.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="p-4">{{ $leaves->links() }}</div>
        </div>

        <div x-show="showApproveModal" class="fixed z-10 inset-0 overflow-y-auto" x-cloak>
            <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center">
                <div class="fixed inset-0 transition-opacity" @click="showApproveModal = false">
                    <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
                </div>
                <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <form :action="formAction" method="POST">
                        @csrf @method('PUT')
                        <input type="hidden" name="status" value="Approved">
                        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                            <h3 class="text-lg leading-6 font-medium text-gray-900">Konfirmasi Persetujuan</h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500">Apakah Anda yakin ingin menyetujui pengajuan cuti dari <strong x-text="selectedLeave ? selectedLeave.user.name : ''"></strong>?</p>
                            </div>
                        </div>
                        <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                            <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-green-600 text-base font-medium text-white hover:bg-green-700 sm:ml-3 sm:w-auto sm:text-sm">Setujui</button>
                            <button type="button" @click="showApproveModal = false" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 sm:mt-0 sm:w-auto sm:text-sm">Batal</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div x-show="showRejectModal" class="fixed z-10 inset-0 overflow-y-auto" x-cloak>
            <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center">
                <div class="fixed inset-0 transition-opacity" @click="showRejectModal = false">
                    <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
                </div>
                <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <form :action="formAction" method="POST">
                        @csrf @method('PUT')
                        <input type="hidden" name="status" value="Rejected">
                        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                            <h3 class="text-lg leading-6 font-medium text-gray-900">Konfirmasi Penolakan</h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500 mb-2">Berikan alasan penolakan untuk pengajuan dari <strong x-text="selectedLeave ? selectedLeave.user.name : ''"></strong>.</p>
                                <textarea name="rejection_reason" x-model="rejectionReason" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-md" placeholder="Alasan penolakan..." required></textarea>
                            </div>
                        </div>
                        <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                            <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 sm:ml-3 sm:w-auto sm:text-sm">Tolak Pengajuan</button>
                            <button type="button" @click="showRejectModal = false; rejectionReason = ''" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 sm:mt-0 sm:w-auto sm:text-sm">Batal</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>