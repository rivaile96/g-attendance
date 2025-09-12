<x-app-layout>
    {{-- Card Widget untuk Absensi --}}
    <div class="bg-white overflow-hidden shadow-md rounded-lg">
        <div class="p-6 text-gray-900">

            <h2 class="text-2xl font-bold text-dark-blue mb-6 border-b pb-4">
                Absensi Kehadiran
            </h2>

            @if (session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-md mb-6" role="alert">
                    <p class="font-bold">Berhasil</p>
                    <p>{{ session('success') }}</p>
                </div>
            @endif
            @if (session('error'))
                 <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded-md mb-6" role="alert">
                    <p class="font-bold">Gagal</p>
                    <p>{{ session('error') }}</p>
                </div>
            @endif

            <div class="text-center">
                @if ($todayAttendance)
                    {{-- Jika user SUDAH check-in hari ini --}}
                    @if ($todayAttendance->check_out)
                        {{-- Dan SUDAH check-out --}}
                        <h3 class="text-lg font-medium text-gray-800">Anda Sudah Menyelesaikan Absensi Hari Ini</h3>
                        <div class="mt-4 p-4 bg-light-gray rounded-lg inline-block text-left">
                            <p><span class="font-semibold">Waktu Masuk:</span> {{ $todayAttendance->check_in->format('H:i:s') }}</p>
                            <p><span class="font-semibold">Waktu Pulang:</span> {{ $todayAttendance->check_out->format('H:i:s') }}</p>
                        </div>
                    @else
                        {{-- Tapi BELUM check-out --}}
                        <h3 class="text-lg font-medium text-gray-800">Anda Sudah Absen Masuk</h3>
                        <p class="mt-2">Waktu Masuk: {{ $todayAttendance->check_in->format('d M Y, H:i:s') }}</p>
                        <form action="{{ route('attendance.checkout') }}" method="POST" class="mt-4">
                            @csrf
                            <button type="submit" class="px-6 py-3 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition ease-in-out duration-150">
                                Absen Pulang Sekarang
                            </button>
                        </form>
                    @endif
                @else
                    {{-- Jika user BELUM check-in sama sekali --}}
                    <h3 class="text-lg font-medium text-gray-800">Silakan Lakukan Absensi Masuk</h3>
                    <div class="mt-4 flex justify-center space-x-4">
                        <form action="{{ route('attendance.checkin') }}" method="POST">
                            @csrf
                            <input type="hidden" name="type" value="wifi">
                            <button type="submit" class="px-6 py-3 bg-dark-blue border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 transition">
                                Absen via WiFi Kantor
                            </button>
                        </form>
                        <button id="btn-gps-checkin" class="px-6 py-3 bg-primary-yellow border border-transparent rounded-md font-semibold text-xs text-dark-blue uppercase tracking-widest hover:opacity-80 transition">
                            Absen via GPS
                        </button>
                    </div>
                    <form id="form-gps-checkin" action="{{ route('attendance.checkin') }}" method="POST" style="display: none;">
                        @csrf
                        <input type="hidden" name="type" value="gps">
                        <input type="hidden" id="latitude" name="latitude">
                        <input type="hidden" id="longitude" name="longitude">
                    </form>
                @endif
            </div>

        </div>
    </div>

    {{-- ========================================================== --}}
    {{-- JAVASCRIPT YANG HILANG SEKARANG SUDAH ADA KEMBALI DI SINI --}}
    {{-- ========================================================== --}}
    @push('scripts')
    <script>
        // Pastikan dokumen sudah siap
        document.addEventListener('DOMContentLoaded', function () {
            const btnGpsCheckin = document.getElementById('btn-gps-checkin');
            const formGpsCheckin = document.getElementById('form-gps-checkin');
            const latitudeInput = document.getElementById('latitude');
            const longitudeInput = document.getElementById('longitude');

            // Cek apakah tombolnya ada di halaman ini
            if (btnGpsCheckin) {
                // Tambahkan 'event listener' saat tombol di-klik
                btnGpsCheckin.addEventListener('click', function () {
                    if (!navigator.geolocation) {
                        alert('Geolocation tidak didukung oleh browser Anda.');
                        return;
                    }

                    // Ubah teks tombol untuk feedback ke user
                    btnGpsCheckin.textContent = 'Mencari Lokasi...';
                    btnGpsCheckin.disabled = true;

                    // Minta lokasi GPS dari browser
                    navigator.geolocation.getCurrentPosition(function (position) {
                        // Jika berhasil, isi form tersembunyi
                        latitudeInput.value = position.coords.latitude;
                        longitudeInput.value = position.coords.longitude;
                        // Kirim form
                        formGpsCheckin.submit();
                    }, function (error) {
                         // Jika gagal, tampilkan error
                         alert('Gagal mendapatkan lokasi. Pastikan Anda memberikan izin akses lokasi.');
                         // Kembalikan tombol ke keadaan semula
                         btnGpsCheckin.textContent = 'Absen via GPS';
                         btnGpsCheckin.disabled = false;
                    });
                });
            }
        });
    </script>
    @endpush
</x-app-layout>