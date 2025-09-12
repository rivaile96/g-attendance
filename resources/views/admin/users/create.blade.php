<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Karyawan Baru</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'dark-blue': '#1e40af',
                        'light-blue': '#3b82f6',
                        'soft-gray': '#f8fafc'
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-gray-100 min-h-screen">
    <div class="container mx-auto px-4 py-8 max-w-5xl">
        <div class="bg-white p-8 rounded-xl shadow-lg">
            <!-- Header -->
            <div class="flex items-center justify-between mb-8 pb-4 border-b">
                <div>
                    <h1 class="text-3xl font-bold text-dark-blue">Tambah Karyawan Baru</h1>
                    <p class="text-gray-600 mt-2">Isi formulir berikut untuk menambahkan karyawan baru</p>
                </div>
                <div class="bg-blue-50 p-3 rounded-lg">
                    <i class="fas fa-user-plus text-dark-blue text-2xl"></i>
                </div>
            </div>
            
            <!-- Alert Error -->
            @if ($errors->any())
                <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-lg mb-6" role="alert">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <i class="fas fa-exclamation-circle text-red-500 mt-1"></i>
                        </div>
                        <div class="ml-3">
                            <p class="font-bold text-red-800">Oops! Terjadi kesalahan</p>
                            <ul class="mt-2 list-disc list-inside text-red-700">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Form -->
            <form action="{{ route('admin.users.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    <!-- Kolom 1: Foto Profil -->
                    <div class="lg:col-span-1">
                        <div class="bg-soft-gray p-6 rounded-xl">
                            <h2 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                                <i class="fas fa-camera mr-2 text-dark-blue"></i> Foto Profil
                            </h2>
                            <div class="flex flex-col items-center">
                                <div class="relative mb-4">
                                    <img id="photo-preview" src="https://ui-avatars.com/api/?name=New+Employee&size=128&background=random" 
                                         alt="Photo Preview" class="w-40 h-40 rounded-full object-cover border-4 border-white shadow-md">
                                    <label for="profile_photo" class="absolute bottom-0 right-0 bg-dark-blue text-white p-2 rounded-full cursor-pointer shadow-md hover:bg-blue-800 transition">
                                        <i class="fas fa-camera text-sm"></i>
                                    </label>
                                </div>
                                <input type="file" name="profile_photo" id="profile_photo" class="hidden" accept="image/*">
                                <p class="text-xs text-gray-500 text-center mt-2">Format: JPG, PNG, GIF<br>Maksimal: 2MB</p>
                                @error('profile_photo') 
                                    <span class="text-red-500 text-sm mt-2 block">{{ $message }}</span> 
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Kolom 2 & 3: Detail Karyawan -->
                    <div class="lg:col-span-2 grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Sub-Kolom 1 -->
                        <div class="space-y-5">
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700 mb-1 flex items-center">
                                    <i class="fas fa-user text-dark-blue mr-2 text-sm"></i> Nama Lengkap <span class="text-red-500 ml-1">*</span>
                                </label>
                                <input type="text" name="name" id="name" value="{{ old('name') }}" required 
                                       class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-dark-blue focus:border-dark-blue transition"
                                       placeholder="Masukkan nama lengkap">
                            </div>
                            
                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700 mb-1 flex items-center">
                                    <i class="fas fa-envelope text-dark-blue mr-2 text-sm"></i> Alamat Email <span class="text-red-500 ml-1">*</span>
                                </label>
                                <input type="email" name="email" id="email" value="{{ old('email') }}" required 
                                       class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-dark-blue focus:border-dark-blue transition"
                                       placeholder="contoh@perusahaan.com">
                            </div>
                            
                            <div>
                                <label for="division_id" class="block text-sm font-medium text-gray-700 mb-1 flex items-center">
                                    <i class="fas fa-building text-dark-blue mr-2 text-sm"></i> Divisi <span class="text-red-500 ml-1">*</span>
                                </label>
                                <select name="division_id" id="division_id" required 
                                        class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-dark-blue focus:border-dark-blue transition">
                                    <option value="">Pilih Divisi</option>
                                    @foreach($divisions as $division)
                                        <option value="{{ $division->id }}" {{ old('division_id') == $division->id ? 'selected' : '' }}>
                                            {{ $division->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <div>
                                <label for="password" class="block text-sm font-medium text-gray-700 mb-1 flex items-center">
                                    <i class="fas fa-lock text-dark-blue mr-2 text-sm"></i> Password <span class="text-red-500 ml-1">*</span>
                                </label>
                                <input type="password" name="password" id="password" required 
                                       class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-dark-blue focus:border-dark-blue transition"
                                       placeholder="Minimal 8 karakter">
                                <p class="text-xs text-gray-500 mt-2">
                                    <i class="fas fa-info-circle mr-1"></i> Password harus minimal 8 karakter.
                                </p>
                            </div>
                        </div>

                        <!-- Sub-Kolom 2 -->
                        <div class="space-y-5">
                            <div>
                                <label for="employment_status" class="block text-sm font-medium text-gray-700 mb-1 flex items-center">
                                    <i class="fas fa-briefcase text-dark-blue mr-2 text-sm"></i> Status Kerja
                                </label>
                                <select name="employment_status" id="employment_status" 
                                        class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-dark-blue focus:border-dark-blue transition">
                                    <option value="Kontrak" {{ old('employment_status') == 'Kontrak' ? 'selected' : '' }}>Kontrak</option>
                                    <option value="Tetap" {{ old('employment_status') == 'Tetap' ? 'selected' : '' }}>Tetap</option>
                                    <option value="Magang" {{ old('employment_status') == 'Magang' ? 'selected' : '' }}>Magang</option>
                                    <option value="Probation" {{ old('employment_status') == 'Probation' ? 'selected' : '' }}>Probation</option>
                                </select>
                            </div>
                            
                            <div>
                                <label for="join_date" class="block text-sm font-medium text-gray-700 mb-1 flex items-center">
                                    <i class="fas fa-calendar-alt text-dark-blue mr-2 text-sm"></i> Tanggal Bergabung
                                </label>
                                <input type="date" name="join_date" id="join_date" value="{{ old('join_date') }}" 
                                       class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-dark-blue focus:border-dark-blue transition">
                            </div>
                            
                            <div>
                                <label for="birth_date" class="block text-sm font-medium text-gray-700 mb-1 flex items-center">
                                    <i class="fas fa-birthday-cake text-dark-blue mr-2 text-sm"></i> Tanggal Lahir
                                </label>
                                <input type="date" name="birth_date" id="birth_date" value="{{ old('birth_date') }}" 
                                       class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-dark-blue focus:border-dark-blue transition">
                            </div>
                            
                            <div>
                                <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1 flex items-center">
                                    <i class="fas fa-lock text-dark-blue mr-2 text-sm"></i> Konfirmasi Password <span class="text-red-500 ml-1">*</span>
                                </label>
                                <input type="password" name="password_confirmation" id="password_confirmation" required 
                                       class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-dark-blue focus:border-dark-blue transition"
                                       placeholder="Ketik ulang password">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tombol Aksi -->
                <div class="mt-10 pt-6 border-t border-gray-200 flex flex-col sm:flex-row justify-end gap-3">
                    <a href="{{ route('admin.users.index') }}" 
                       class="px-6 py-3 bg-gray-100 text-gray-700 rounded-lg font-medium hover:bg-gray-200 transition flex items-center justify-center">
                        <i class="fas fa-times mr-2"></i> Batal
                    </a>
                    <button type="submit" 
                            class="px-6 py-3 bg-dark-blue text-white rounded-lg font-medium hover:bg-blue-800 transition flex items-center justify-center">
                        <i class="fas fa-save mr-2"></i> Simpan Karyawan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Script untuk preview foto
        document.getElementById('profile_photo').addEventListener('change', function(event) {
            const [file] = event.target.files;
            if (file) {
                const preview = document.getElementById('photo-preview');
                preview.src = URL.createObjectURL(file);
                preview.onload = () => URL.revokeObjectURL(preview.src);
            }
        });
        
        // Menambahkan efek interaktif pada form input
        document.querySelectorAll('input, select').forEach(element => {
            element.addEventListener('focus', () => {
                element.parentElement.classList.add('ring-2', 'ring-blue-200');
            });
            
            element.addEventListener('blur', () => {
                element.parentElement.classList.remove('ring-2', 'ring-blue-200');
            });
        });
        
        // Validasi konfirmasi password
        const password = document.getElementById('password');
        const confirmPassword = document.getElementById('password_confirmation');
        
        function validatePassword() {
            if (password.value !== confirmPassword.value) {
                confirmPassword.setCustomValidity('Password tidak sesuai');
            } else {
                confirmPassword.setCustomValidity('');
            }
        }
        
        password.addEventListener('change', validatePassword);
        confirmPassword.addEventListener('keyup', validatePassword);
    </script>
</body>
</html>