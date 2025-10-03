<x-app-layout>
    <div class="max-w-3xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
        
        {{-- Menampilkan notifikasi dari Controller (setelah redirect) --}}
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

        {{-- Logika utama untuk menampilkan konten --}}
        @if (!$todayAttendance)
            {{-- TAMPILAN JIKA USER BELUM CHECK-IN --}}
            <div class="flex flex-col items-center justify-center text-center">
                
                <div class="mb-8">
                    <h1 id="clock" class="text-6xl md:text-8xl font-bold text-dark-blue tracking-tight"></h1>
                    <p id="date" class="text-lg md:text-xl text-gray-500 mt-2"></p>
                </div>

                <p class="mb-6 text-gray-600">Silakan Lakukan Absensi Masuk</p>
                <div class="flex flex-col sm:flex-row items-center gap-4">
                    {{-- Form untuk Absen via WiFi --}}
                    <form action="{{ route('attendance.checkin') }}" method="POST">
                        @csrf
                        <input type="hidden" name="type" value="wifi">
                        <button type="submit" class="w-48 px-6 py-3 bg-dark-blue text-white font-semibold rounded-lg shadow-md hover:bg-opacity-90 transition duration-300">
                            <i class="fa-solid fa-wifi mr-2"></i>
                            Absen via WiFi
                        </button>
                    </form>

                    {{-- Form untuk Absen via GPS (lebih rapi) --}}
                    <form action="{{ route('attendance.checkin') }}" method="POST" id="gps-form">
                        @csrf
                        <input type="hidden" name="type" value="gps">
                        <input type="hidden" name="latitude" id="latitude">
                        <input type="hidden" name="longitude" id="longitude">
                        <button type="submit" id="gps-button" class="w-48 px-6 py-3 bg-primary-yellow text-dark-blue font-semibold rounded-lg shadow-md hover:bg-opacity-90 transition duration-300">
                            <i class="fa-solid fa-location-crosshairs mr-2"></i>
                            Absen via GPS
                        </button>
                    </form>
                </div>
            </div>
        @elseif (!$todayAttendance->check_out)
            {{-- TAMPILAN JIKA SUDAH CHECK-IN TAPI BELUM CHECK-OUT --}}
            <div class="bg-white p-8 rounded-xl shadow-lg text-center">
                <i class="fa-solid fa-right-to-bracket text-blue-500 text-6xl mb-4"></i>
                <h1 class="text-2xl font-bold text-dark-blue">Anda Sudah Absen Masuk</h1>
                <p class="text-gray-600 mt-2">
                    Pada: <span class="font-semibold">{{ $todayAttendance->check_in->translatedFormat('d F Y, H:i:s') }}</span>
                </p>
                <form action="{{ route('attendance.checkout') }}" method="POST" class="mt-6">
                    @csrf
                    <button type="submit" class="w-full sm:w-auto px-8 py-3 bg-red-600 text-white font-semibold rounded-lg shadow-md hover:bg-red-700 transition duration-300">
                        <i class="fa-solid fa-right-from-bracket mr-2"></i>
                        Check-Out Sekarang
                    </button>
                </form>
            </div>
        @else
            {{-- TAMPILAN JIKA SUDAH CHECK-IN DAN CHECK-OUT --}}
            <div class="bg-white p-8 rounded-xl shadow-lg text-center">
                <i class="fa-solid fa-circle-check text-green-500 text-6xl mb-4"></i>
                <h1 class="text-2xl font-bold text-dark-blue">Absensi Hari Ini Selesai</h1>
                <div class="mt-4 p-4 bg-light-gray rounded-lg inline-block text-left space-y-1">
                    <p><span class="font-semibold">Waktu Masuk:</span> {{ $todayAttendance->check_in->format('H:i:s') }}</p>
                    <p><span class="font-semibold">Waktu Pulang:</span> {{ $todayAttendance->check_out->format('H:i:s') }}</p>
                </div>
                <p class="mt-6 text-sm text-gray-500">Terima kasih dan selamat beristirahat!</p>
            </div>
        @endif
    </div>

    @push('scripts')
    <script>
        // Fungsi untuk jam & tanggal real-time
        function updateTime() {
            const clockEl = document.getElementById('clock');
            const dateEl = document.getElementById('date');
            if (!clockEl || !dateEl) return;
            const now = new Date();
            clockEl.textContent = now.toLocaleTimeString('en-GB');
            dateEl.textContent = now.toLocaleDateString('id-ID', {
                weekday: 'long', year: 'numeric', month: 'long', day: 'numeric'
            });
        }
        updateTime();
        setInterval(updateTime, 1000);

        // Script untuk fungsionalitas Absen GPS
        const gpsForm = document.getElementById('gps-form');
        const gpsButton = document.getElementById('gps-button');

        if (gpsForm) {
            gpsForm.addEventListener('submit', function(event) {
                event.preventDefault();

                gpsButton.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Mencari Lokasi...';
                gpsButton.disabled = true;

                if (!navigator.geolocation) {
                    Swal.fire('Gagal', 'Geolocation tidak didukung oleh browser Anda.', 'error');
                    gpsButton.innerHTML = '<i class="fa-solid fa-location-crosshairs mr-2"></i> Absen via GPS';
                    gpsButton.disabled = false;
                    return;
                }

                navigator.geolocation.getCurrentPosition(function(position) {
                    // Jika BERHASIL, isi form dan submit
                    document.getElementById('latitude').value = position.coords.latitude;
                    document.getElementById('longitude').value = position.coords.longitude;
                    gpsForm.submit();
                }, function() {
                    // Jika GAGAL, tampilkan popup SweetAlert2
                    Swal.fire('Gagal', 'Tidak bisa mendapatkan lokasi Anda. Pastikan izin lokasi sudah diberikan dan coba lagi.', 'error');
                    gpsButton.innerHTML = '<i class="fa-solid fa-location-crosshairs mr-2"></i> Absen via GPS';
                    gpsButton.disabled = false;
                });
            });
        }
    </script>
    @endpush
</x-app-layout>