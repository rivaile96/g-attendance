<x-app-layout>
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 py-8">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold text-dark-blue">Daftar Klaim Lembur Karyawan</h1>
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
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Durasi</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi / Info</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($overtimeLogs as $log)
                    <tr @click="openId = (openId === {{ $log->id }}) ? null : {{ $log->id }}" class="hover:bg-gray-50 cursor-pointer">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $log->user->name }}</div>
                            <div class="text-sm text-gray-500">{{ optional($log->user->division)->name }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $log->start_time->translatedFormat('d M Y') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ \Carbon\Carbon::parse($log->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($log->end_time)->format('H:i') }}
                            <div class="text-xs text-gray-400">({{ $log->start_time->diffInMinutes($log->end_time) }} menit)</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span @class([
                                'px-2 inline-flex text-xs leading-5 font-semibold rounded-full',
                                'bg-yellow-100 text-yellow-800' => $log->status === 'Pending',
                                'bg-green-100 text-green-800' => $log->status === 'Approved',
                                'bg-red-100 text-red-800' => $log->status === 'Rejected',
                            ])>
                                {{ $log->status }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            @if ($log->status === 'Pending')
                                <div @click.stop class="flex items-center space-x-2">
                                    <button @click="showApprovalConfirmation({{ json_encode($log) }}, 'Approved')" type="button" class="text-indigo-600 hover:text-indigo-900 focus:outline-none">Approve</button>
                                    <span class="text-gray-300">|</span>
                                    <button @click="showApprovalConfirmation({{ json_encode($log) }}, 'Rejected')" type="button" class="text-red-600 hover:text-red-900 focus:outline-none">Reject</button>
                                </div>
                            @else
                                <span class="text-sm text-gray-500">Diproses oleh {{ optional($log->approvedBy)->name }}</span>
                            @endif
                        </td>
                    </tr>
                    
                    <tr x-show="openId === {{ $log->id }}" x-collapse style="display: none;">
                        <td colspan="5" class="p-4 bg-blue-50 border-y border-blue-200">
                            <div class="text-sm text-gray-800">
                                <strong class="block font-semibold text-dark-blue mb-2">Catatan dari Karyawan:</strong>
                                <p class="whitespace-pre-wrap pl-2 border-l-2 border-blue-300">{{ $log->notes ?: 'Tidak ada catatan.' }}</p>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="text-center py-6 text-gray-500">Belum ada klaim lembur yang diajukan.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
         <div class="mt-4">
            {{ $overtimeLogs->links() }}
        </div>
    </div>

    <form id="approval-form" method="POST" class="hidden">
        @csrf
        @method('PUT')
        <input type="hidden" name="status" id="approval-status">
    </form>
</x-app-layout>