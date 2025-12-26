<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | WashFlow - Sistem Manajemen Laundry</title>
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
        
        <!-- Left Side - Brand & Features -->
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
                <h2 class="text-2xl font-bold mb-6">Kelola Laundry dengan Lebih Mudah & Efisien</h2>
                <p class="text-blue-100 mb-8 leading-relaxed">
                    Sistem manajemen laundry berbasis web untuk mencatat cucian masuk, memantau status proses secara real-time, dan mengelola pelanggan dengan efisien.
                </p>

                <!-- Features List -->
                <div class="space-y-4">
                    <div class="flex items-center">
                        <div class="bg-blue-500 p-2 rounded-lg mr-4">
                            <i class="fas fa-clock text-white text-sm"></i>
                        </div>
                        <span class="font-medium">Pantau Status Cucian Real-time</span>
                    </div>
                    <div class="flex items-center">
                        <div class="bg-blue-500 p-2 rounded-lg mr-4">
                            <i class="fas fa-clipboard-list text-white text-sm"></i>
                        </div>
                        <span class="font-medium">Kelola Order dan Pelanggan</span>
                    </div>
                    <div class="flex items-center">
                        <div class="bg-blue-500 p-2 rounded-lg mr-4">
                            <i class="fas fa-chart-line text-white text-sm"></i>
                        </div>
                        <span class="font-medium">Analisis & Laporan Keuangan</span>
                    </div>
                    <div class="flex items-center">
                        <div class="bg-blue-500 p-2 rounded-lg mr-4">
                            <i class="fas fa-history text-white text-sm"></i>
                        </div>
                        <span class="font-medium">Riwayat Transaksi Lengkap</span>
                    </div>
                </div>

                <!-- Stats -->
                <div class="mt-12 grid grid-cols-3 gap-4 text-center">
                    <div class="bg-blue-500/20 p-3 rounded-lg">
                        <p class="text-2xl font-bold">100+</p>
                        <p class="text-blue-100 text-xs">Laundry Mitra</p>
                    </div>
                    <div class="bg-blue-500/20 p-3 rounded-lg">
                        <p class="text-2xl font-bold">50K+</p>
                        <p class="text-blue-100 text-xs">Transaksi</p>
                    </div>
                    <div class="bg-blue-500/20 p-3 rounded-lg">
                        <p class="text-2xl font-bold">99%</p>
                        <p class="text-blue-100 text-xs">Kepuasan</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Side - Login Form -->
        <div class="bg-white p-8 lg:p-12 lg:w-3/5">
            <div class="max-w-md mx-auto">
                <!-- Welcome Header -->
                <div class="text-center mb-10">
                    <h2 class="text-3xl font-bold text-gray-800 mb-2">Selamat Datang Kembali</h2>
                    <p class="text-gray-600">Masuk untuk mengelola bisnis laundry Anda</p>
                </div>

                <!-- Error Messages -->
                @if ($errors->any())
                <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded">
                    <div class="flex items-center">
                        <i class="fas fa-exclamation-circle text-red-500 text-xl mr-3"></i>
                        <div>
                            <p class="font-semibold text-red-800">Login Gagal</p>
                            <p class="text-red-700 text-sm">{{ $errors->first() }}</p>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Login Form -->
                <form method="POST" action="{{ route('login.post') }}" class="space-y-6">
                    @csrf

                    <!-- Email Input -->
                    <div>
                        <label class="block text-gray-700 font-medium mb-2">
                            <i class="fas fa-envelope text-blue-500 mr-2"></i>Alamat Email
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-user text-gray-400"></i>
                            </div>
                            <input type="email" name="email" value="{{ old('email') }}"
                                   class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-300"
                                   placeholder="contoh@email.com"
                                   required autofocus>
                        </div>
                    </div>

                    <!-- Password Input -->
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
                                   placeholder="Masukkan password"
                                   required>
                            <button type="button" onclick="togglePassword()" 
                                    class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600">
                                <i class="fas fa-eye" id="toggleIcon"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Remember & Forgot Password -->
                    <div class="flex flex-col sm:flex-row justify-between items-center">
                        <div class="flex items-center mb-4 sm:mb-0">
                            <input type="checkbox" name="remember" id="remember" 
                                   class="h-5 w-5 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                            <label for="remember" class="ml-2 text-gray-700">
                                Ingat saya di perangkat ini
                            </label>
                        </div>
                        <a href="#" class="text-blue-600 hover:text-blue-800 font-medium text-sm">
                            <i class="fas fa-question-circle mr-1"></i>Lupa Password?
                        </a>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit"
                            class="w-full bg-gradient-to-r from-blue-600 to-indigo-600 text-white py-3.5 rounded-lg font-semibold hover:from-blue-700 hover:to-indigo-700 transition duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                        <i class="fas fa-sign-in-alt mr-2"></i>Masuk ke Dashboard
                    </button>

                    <!-- Demo Accounts -->
                    <div class="bg-blue-50 border border-blue-200 rounded-xl p-4 mt-6">
                        <div class="flex items-center mb-2">
                            <i class="fas fa-info-circle text-blue-600 mr-2"></i>
                            <p class="font-medium text-blue-800">Akun Demo untuk Testing:</p>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3 text-sm">
                            <div class="bg-white p-3 rounded-lg">
                                <p class="font-semibold text-blue-700">Admin</p>
                                <p class="text-gray-600">admin@washflow.com</p>
                                <p class="text-gray-600">Password: admin123</p>
                            </div>
                            <div class="bg-white p-3 rounded-lg">
                                <p class="font-semibold text-blue-700">Staff</p>
                                <p class="text-gray-600">staff@washflow.com</p>
                                <p class="text-gray-600">Password: staff123</p>
                            </div>
                        </div>
                    </div>

                    <!-- Register Link -->
                    <div class="text-center pt-6 border-t border-gray-200 mt-8">
                        <p class="text-gray-600">
                            Belum punya akun?
                            <a href="{{ route('register') }}" 
                               class="text-blue-600 hover:text-blue-800 font-semibold ml-1 hover:underline">
                                Daftar sebagai mitra WashFlow
                                <i class="fas fa-arrow-right ml-1"></i>
                            </a>
                        </p>
                    </div>
                </form>

                <!-- Footer -->
                <div class="mt-10 pt-6 border-t border-gray-200">
                    <div class="flex flex-col md:flex-row justify-between items-center">
                        <p class="text-sm text-gray-500 mb-2 md:mb-0">
                            &copy; {{ date('Y') }} WashFlow. All rights reserved.
                        </p>
                        <div class="flex space-x-4">
                            <a href="#" class="text-gray-400 hover:text-blue-600">
                                <i class="fab fa-facebook text-lg"></i>
                            </a>
                            <a href="#" class="text-gray-400 hover:text-blue-400">
                                <i class="fab fa-twitter text-lg"></i>
                            </a>
                            <a href="#" class="text-gray-400 hover:text-pink-600">
                                <i class="fab fa-instagram text-lg"></i>
                            </a>
                        </div>
                    </div>
                    <p class="text-xs text-gray-400 mt-2 text-center md:text-left">
                        Version 2.1.0 • Mengoptimalkan operasional laundry Anda sejak 2023
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript untuk toggle password -->
    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const toggleIcon = document.getElementById('toggleIcon');
            
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