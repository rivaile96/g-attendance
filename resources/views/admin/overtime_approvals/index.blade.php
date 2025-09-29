<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Persetujuan Klaim Lembur') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-bold mb-4">Daftar Klaim Lembur Karyawan</h3>
                    
                    {{-- Notifikasi Sukses --}}
                    @if (session('success'))
                        <div class="mb-4 p-4 bg-green-100 text-green-700 border border-green-200 rounded-md">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Karyawan</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Durasi</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($overtimeLogs as $log)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">{{ $log->user->name }}</div>
                                            <div class="text-sm text-gray-500">{{ optional($log->user->division)->name ?? 'N/A' }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $log->start_time->translatedFormat('d M Y') }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">{{ $log->start_time->format('H:i') }} - {{ $log->end_time->format('H:i') }}</div>
                                            <div class="text-sm text-gray-500">{{ $log->end_time->diffForHumans($log->start_time, true) }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @php
                                                $statusClass = [
                                                    'Pending' => 'bg-yellow-100 text-yellow-800',
                                                    'Approved' => 'bg-green-100 text-green-800',
                                                    'Rejected' => 'bg-red-100 text-red-800',
                                                ][$log->status] ?? 'bg-gray-100 text-gray-800';
                                            @endphp
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusClass }}">
                                                {{ $log->status }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            @if ($log->status == 'Pending')
                                                <div class="flex items-center space-x-2">
                                                    {{-- Form untuk Approve --}}
                                                    <form method="POST" action="{{ route('admin.overtime-approvals.update', $log) }}" class="inline-block needs-confirmation" data-message="Anda yakin ingin menyetujui klaim ini?">
                                                        @csrf
                                                        @method('PUT')
                                                        <input type="hidden" name="status" value="Approved">
                                                        <button type="submit" class="text-indigo-600 hover:text-indigo-900">Approve</button>
                                                    </form>
                                                    
                                                    <span>|</span>

                                                    {{-- Form untuk Reject --}}
                                                    <form method="POST" action="{{ route('admin.overtime-approvals.update', $log) }}" class="inline-block needs-confirmation" data-message="Anda yakin ingin menolak klaim ini?">
                                                        @csrf
                                                        @method('PUT')
                                                        <input type="hidden" name="status" value="Rejected">
                                                        <button type="submit" class="text-red-600 hover:text-red-900">Reject</button>
                                                    </form>
                                                </div>
                                            @else
                                                <span class="text-xs text-gray-500">Diproses oleh {{ optional($log->approvedBy)->name ?? 'N/A' }}</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                                            Tidak ada klaim lembur yang perlu diproses.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4">
                        {{ $overtimeLogs->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

@push('scripts')
<script>
    // Pastikan script berjalan setelah semua elemen halaman dimuat
    document.addEventListener('DOMContentLoaded', function () {
        // Cari semua form yang punya class 'needs-confirmation'
        const confirmationForms = document.querySelectorAll('.needs-confirmation');

        // Loop setiap form dan tambahkan event listener
        confirmationForms.forEach(form => {
            form.addEventListener('submit', function (event) {
                // 1. Hentikan pengiriman form asli
                event.preventDefault();

                // 2. Ambil pesan dari atribut data-message
                const message = event.target.dataset.message;

                // 3. Tampilkan SweetAlert2
                Swal.fire({
                    title: 'Konfirmasi',
                    text: message,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, Lanjutkan!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    // 4. Jika user mengklik "Ya", kirim form-nya
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });
    });
</script>
@endpush