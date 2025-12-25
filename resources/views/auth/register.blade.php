<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register | WashFlow - Sistem Manajemen Laundry</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>
<body class="bg-gradient-to-br from-blue-50 to-indigo-50 min-h-screen flex items-center justify-center p-4">
    
    <div class="w-full max-w-6xl flex flex-col lg:flex-row rounded-2xl overflow-hidden shadow-2xl">
        
        <!-- Left Side - Brand & Benefits -->
        <div class="bg-gradient-to-br from-blue-600 to-indigo-700 text-white p-8 lg:p-12 lg:w-2/5 flex flex-col justify-center relative overflow-hidden">
            <!-- Water Wave Effect Background -->
            <div class="absolute inset-0 opacity-10">
                <div class="absolute -top-24 -left-24 w-64 h-64 bg-blue-400 rounded-full"></div>
                <div class="absolute top-1/2 right-0 w-48 h-48 bg-blue-300 rounded-full"></div>
                <div class="absolute bottom-0 left-1/3 w-56 h-56 bg-indigo-400 rounded-full"></div>
            </div>
            
            <div class="relative z-10">
                <!-- Logo & Brand -->
                <div class="flex items-center mb-8">
                    <div class="bg-white p-3 rounded-xl shadow-lg mr-4">
                        <i class="fas fa-tshirt text-blue-600 text-2xl"></i>
                    </div>
                    <div>
                        <h1 class="text-3xl font-bold tracking-tight">WashFlow</h1>
                        <p class="text-blue-100 text-sm">Laundry Management System</p>
                    </div>
                </div>

                <!-- Tagline -->
                <h2 class="text-2xl font-bold mb-6">Bergabung dengan Komunitas Laundry Terbaik</h2>
                <p class="text-blue-100 mb-8 leading-relaxed">
                    Tingkatkan efisiensi bisnis laundry Anda dengan sistem manajemen terintegrasi yang dirancang khusus untuk kebutuhan laundry modern.
                </p>

                <!-- Benefits List -->
                <div class="space-y-4">
                    <div class="flex items-center">
                        <div class="bg-blue-500 p-2 rounded-lg mr-4">
                            <i class="fas fa-rocket text-white text-sm"></i>
                        </div>
                        <span class="font-medium">Tingkatkan Produktivitas 3x Lebih Cepat</span>
                    </div>
                    <div class="flex items-center">
                        <div class="bg-blue-500 p-2 rounded-lg mr-4">
                            <i class="fas fa-chart-pie text-white text-sm"></i>
                        </div>
                        <span class="font-medium">Analisis Data & Laporan Real-time</span>
                    </div>
                    <div class="flex items-center">
                        <div class="bg-blue-500 p-2 rounded-lg mr-4">
                            <i class="fas fa-headset text-white text-sm"></i>
                        </div>
                        <span class="font-medium">Dukungan Teknis 24/7</span>
                    </div>
                    <div class="flex items-center">
                        <div class="bg-blue-500 p-2 rounded-lg mr-4">
                            <i class="fas fa-mobile-alt text-white text-sm"></i>
                        </div>
                        <span class="font-medium">Akses dari Desktop & Mobile</span>
                    </div>
                </div>

                <!-- Testimonial -->
                <div class="mt-12 bg-blue-500/20 p-6 rounded-xl">
                    <div class="flex items-center mb-4">
                        <div class="w-10 h-10 bg-blue-400 rounded-full flex items-center justify-center mr-3">
                            <i class="fas fa-user text-white"></i>
                        </div>
                        <div>
                            <p class="font-semibold">Budi Laundry Center</p>
                            <p class="text-blue-100 text-sm">Mitra sejak 2022</p>
                        </div>
                    </div>
                    <p class="italic text-blue-100">
                        "Dengan WashFlow, bisnis laundry saya naik 40% dalam 3 bulan pertama. Sistem yang sangat membantu!"
                    </p>
                </div>
            </div>
        </div>

        <!-- Right Side - Register Form -->
        <div class="bg-white p-8 lg:p-12 lg:w-3/5">
            <div class="max-w-md mx-auto">
                <!-- Welcome Header -->
                <div class="text-center mb-8">
                    <h2 class="text-3xl font-bold text-gray-800 mb-2">Mulai Perjalanan Bisnis Anda</h2>
                    <p class="text-gray-600">Daftar akun untuk mengelola laundry dengan lebih efisien</p>
                </div>

                <!-- Success Message (if any) -->
                @if (session('success'))
                <div class="mb-6 bg-green-50 border-l-4 border-green-500 p-4 rounded">
                    <div class="flex items-center">
                        <i class="fas fa-check-circle text-green-500 text-xl mr-3"></i>
                        <div>
                            <p class="font-semibold text-green-800">Berhasil!</p>
                            <p class="text-green-700 text-sm">{{ session('success') }}</p>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Register Form -->
                <form method="POST" action="{{ route('register') }}" class="space-y-6">
                    @csrf

                    <!-- Full Name -->
                    <div>
                        <label class="block text-gray-700 font-medium mb-2">
                            <i class="fas fa-user text-blue-500 mr-2"></i>Nama Lengkap
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-id-card text-gray-400"></i>
                            </div>
                            <input type="text" name="name" value="{{ old('name') }}"
                                   class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-300"
                                   placeholder="Masukkan nama lengkap"
                                   required>
                        </div>
                        @error('name')
                            <p class="mt-2 text-sm text-red-600">
                                <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div>
                        <label class="block text-gray-700 font-medium mb-2">
                            <i class="fas fa-envelope text-blue-500 mr-2"></i>Alamat Email
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-at text-gray-400"></i>
                            </div>
                            <input type="email" name="email" value="{{ old('email') }}"
                                   class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-300"
                                   placeholder="contoh@email.com"
                                   required>
                        </div>
                        @error('email')
                            <p class="mt-2 text-sm text-red-600">
                                <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div>
                        <label class="block text-gray-700 font-medium mb-2">
                            <i class="fas fa-lock text-blue-500 mr-2"></i>Password
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-key text-gray-400"></i>
                            </div>
                            <input type="password" name="password" id="password"
                                   class="w-full pl-10 pr-12 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-300"
                                   placeholder="Minimal 8 karakter"
                                   required>
                            <button type="button" onclick="togglePassword('password')" 
                                    class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600">
                                <i class="fas fa-eye" id="toggleIconPassword"></i>
                            </button>
                        </div>
                        <div class="mt-2 grid grid-cols-2 gap-2">
                            <div class="flex items-center">
                                <i id="lengthCheck" class="fas fa-times text-red-400 text-xs mr-1"></i>
                                <span class="text-xs text-gray-600">Min. 8 karakter</span>
                            </div>
                        </div>
                        @error('password')
                            <p class="mt-2 text-sm text-red-600">
                                <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Confirm Password -->
                    <div>
                        <label class="block text-gray-700 font-medium mb-2">
                            <i class="fas fa-lock text-blue-500 mr-2"></i>Konfirmasi Password
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-key text-gray-400"></i>
                            </div>
                            <input type="password" name="password_confirmation" id="password_confirmation"
                                   class="w-full pl-10 pr-12 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-300"
                                   placeholder="Ulangi password"
                                   required>
                            <button type="button" onclick="togglePassword('password_confirmation')" 
                                    class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600">
                                <i class="fas fa-eye" id="toggleIconConfirm"></i>
                            </button>
                        </div>
                        @error('password_confirmation')
                            <p class="mt-2 text-sm text-red-600">
                                <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Terms & Conditions -->
                    <div class="flex items-start">
                        <input type="checkbox" name="terms" id="terms" 
                               class="h-5 w-5 text-blue-600 border-gray-300 rounded focus:ring-blue-500 mt-1"
                               required>
                        <label for="terms" class="ml-3 text-sm text-gray-700">
                            Saya setuju dengan 
                            <a href="#" class="text-blue-600 hover:text-blue-800 font-medium">Syarat & Ketentuan</a>
                            dan 
                            <a href="#" class="text-blue-600 hover:text-blue-800 font-medium">Kebijakan Privasi</a>
                            WashFlow
                        </label>
                    </div>
                    @error('terms')
                        <p class="mt-2 text-sm text-red-600">
                            <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                        </p>
                    @enderror

                    <!-- Submit Button -->
                    <button type="submit" id="submitBtn"
                            class="w-full bg-gradient-to-r from-blue-600 to-indigo-600 text-white py-3.5 rounded-lg font-semibold hover:from-blue-700 hover:to-indigo-700 transition duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 disabled:opacity-50 disabled:cursor-not-allowed">
                        <i class="fas fa-user-plus mr-2"></i>Daftar Sekarang
                    </button>

                    <!-- Login Link -->
                    <div class="text-center pt-6 border-t border-gray-200 mt-8">
                        <p class="text-gray-600">
                            Sudah punya akun?
                            <a href="{{ route('login') }}" 
                               class="text-blue-600 hover:text-blue-800 font-semibold ml-1 hover:underline">
                                Masuk di sini
                                <i class="fas fa-arrow-right ml-1"></i>
                            </a>
                        </p>
                    </div>
                </form>

                <!-- Footer -->
                <div class="mt-8 pt-6 border-t border-gray-200">
                    <div class="flex flex-col md:flex-row justify-between items-center">
                        <p class="text-sm text-gray-500 mb-2 md:mb-0">
                            &copy; {{ date('Y') }} WashFlow. All rights reserved.
                        </p>
                        <div class="flex space-x-4">
                            <a href="#" class="text-gray-400 hover:text-blue-600 text-sm">
                                <i class="fas fa-shield-alt mr-1"></i>Keamanan
                            </a>
                            <a href="#" class="text-gray-400 hover:text-blue-600 text-sm">
                                <i class="fas fa-question-circle mr-1"></i>Bantuan
                            </a>
                            <a href="#" class="text-gray-400 hover:text-blue-600 text-sm">
                                <i class="fas fa-phone mr-1"></i>Kontak
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript -->
    <script>
        // Toggle password visibility
        function togglePassword(fieldId) {
            const passwordInput = document.getElementById(fieldId);
            const toggleIcon = document.getElementById(`toggleIcon${fieldId === 'password' ? 'Password' : 'Confirm'}`);
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleIcon.classList.remove('fa-eye');
                toggleIcon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                toggleIcon.classList.remove('fa-eye-slash');
                toggleIcon.classList.add('fa-eye');
            }
        }

        // Password strength checker
        document.getElementById('password').addEventListener('input', function(e) {
            const password = e.target.value;
            const lengthCheck = document.getElementById('lengthCheck');
            
            // Check minimum length
            if (password.length >= 8) {
                lengthCheck.classList.remove('fa-times', 'text-red-400');
                lengthCheck.classList.add('fa-check', 'text-green-500');
            } else {
                lengthCheck.classList.remove('fa-check', 'text-green-500');
                lengthCheck.classList.add('fa-times', 'text-red-400');
            }
            
            // Check password match
            const confirmPassword = document.getElementById('password_confirmation').value;
            const submitBtn = document.getElementById('submitBtn');
            
            if (confirmPassword !== '' && password !== confirmPassword) {
                submitBtn.disabled = true;
                submitBtn.classList.add('disabled:opacity-50', 'disabled:cursor-not-allowed');
            } else {
                submitBtn.disabled = false;
                submitBtn.classList.remove('disabled:opacity-50', 'disabled:cursor-not-allowed');
            }
        });

        // Confirm password checker
        document.getElementById('password_confirmation').addEventListener('input', function(e) {
            const password = document.getElementById('password').value;
            const confirmPassword = e.target.value;
            const submitBtn = document.getElementById('submitBtn');
            
            if (password !== confirmPassword) {
                submitBtn.disabled = true;
                submitBtn.classList.add('disabled:opacity-50', 'disabled:cursor-not-allowed');
            } else {
                submitBtn.disabled = false;
                submitBtn.classList.remove('disabled:opacity-50', 'disabled:cursor-not-allowed');
            }
        });

        // Terms and conditions checkbox
        document.getElementById('terms').addEventListener('change', function(e) {
            const submitBtn = document.getElementById('submitBtn');
            if (!e.target.checked) {
                submitBtn.disabled = true;
                submitBtn.classList.add('disabled:opacity-50', 'disabled:cursor-not-allowed');
            } else {
                const password = document.getElementById('password').value;
                const confirmPassword = document.getElementById('password_confirmation').value;
                if (password === confirmPassword) {
                    submitBtn.disabled = false;
                    submitBtn.classList.remove('disabled:opacity-50', 'disabled:cursor-not-allowed');
                }
            }
        });

        // Animasi untuk input focus
        document.querySelectorAll('input').forEach(input => {
            input.addEventListener('focus', function() {
                this.parentElement.classList.add('ring-2', 'ring-blue-200');
            });
            
            input.addEventListener('blur', function() {
                this.parentElement.classList.remove('ring-2', 'ring-blue-200');
            });
        });
    </script>

</body>
</html>