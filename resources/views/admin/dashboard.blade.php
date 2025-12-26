<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin | WashFlow - Sistem Manajemen Laundry</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            font-family: 'Inter', sans-serif;
        }
        .card-hover:hover {
            transform: translateY(-5px);
            transition: transform 0.3s ease;
        }
    </style>
</head>
<body class="bg-gray-50">
    
    <!-- Navbar Admin -->
    <nav class="bg-gradient-to-r from-blue-600 to-indigo-700 text-white shadow-lg">
        <div class="container mx-auto px-4 py-3">
            <div class="flex justify-between items-center">
                <!-- Logo & Brand -->
                <div class="flex items-center space-x-3">
                    <div class="bg-white p-2 rounded-lg">
                        <i class="fas fa-tshirt text-blue-600 text-xl"></i>
                    </div>
                    <div>
                        <h1 class="text-xl font-bold">WashFlow</h1>
                        <p class="text-blue-100 text-xs">Admin Dashboard</p>
                    </div>
                </div>

                <!-- Info Admin -->
                <div class="flex items-center space-x-6">
                    <div class="text-center">
                        <p class="text-xs text-blue-200">Admin</p>
                        <p class="font-bold">{{ auth()->user()->name }}</p>
                    </div>
                    <div class="text-center">
                        <p class="text-xs text-blue-200">Tanggal</p>
                        <p class="font-bold">{{ date('d/m/Y') }}</p>
                    </div>
                </div>

                <!-- User Menu -->
                <div class="flex items-center space-x-4">
                    <!-- Switch to Cashier -->
                    <a href="{{ route('dashboard') }}" class="bg-yellow-500 text-white px-4 py-2 rounded-lg hover:bg-yellow-600 font-medium">
                        <i class="fas fa-exchange-alt mr-2"></i>Kasir Mode
                    </a>
                    
                    <!-- User Profile -->
                    <div class="relative" id="userMenuWrapper">
                        <button id="userMenuButton" onclick="toggleUserMenu(event)" class="flex items-center space-x-2 focus:outline-none" type="button" aria-expanded="false" aria-controls="userMenu">
                            <div class="w-10 h-10 bg-white rounded-full flex items-center justify-center">
                                <i class="fas fa-user text-blue-600"></i>
                            </div>
                        </button>
                        <div id="userMenu" class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg py-2 hidden z-50">
                            <div class="px-4 py-2 border-b">
                                <p class="font-medium text-gray-800">{{ auth()->user()->name }}</p>
                                <p class="text-xs text-gray-500">Admin</p>
                            </div>
                            <a href="{{ route('admin.dashboard') }}" class="block px-4 py-2 text-blue-600 hover:bg-blue-50">
                                <i class="fas fa-tachometer-alt mr-2"></i>Dashboard
                            </a>
                            <a href="{{ route('admin.reports') }}" class="block px-4 py-2 text-blue-600 hover:bg-blue-50">
                                <i class="fas fa-chart-bar mr-2"></i>Laporan
                            </a>
                            <a href="{{ route('admin.staff') }}" class="block px-4 py-2 text-blue-600 hover:bg-blue-50">
                                <i class="fas fa-users mr-2"></i>Staff
                            </a>
                            <a href="{{ route('admin.services') }}" class="block px-4 py-2 text-blue-600 hover:bg-blue-50">
                                <i class="fas fa-concierge-bell mr-2"></i>Layanan
                            </a>
                            <div class="border-t"></div>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="w-full text-left px-4 py-2 text-red-600 hover:bg-red-50">
                                    <i class="fas fa-sign-out-alt mr-2"></i>Logout
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container mx-auto px-4 py-6">
        <!-- Welcome Header -->
        <div class="mb-8">
            <div class="flex justify-between items-center mb-4">
                <div>
                    <h1 class="text-3xl font-bold text-gray-800">
                        Selamat datang, <span class="text-blue-600">{{ auth()->user()->name }}</span>!
                    </h1>
                    <p class="text-gray-600">Monitor performa bisnis laundry Anda secara real-time</p>
                </div>
                <div class="flex space-x-4">
                    <button onclick="window.print()" class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700">
                        <i class="fas fa-print mr-2"></i>Print Report
                    </button>
                    <a href="{{ route('admin.reports') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                        <i class="fas fa-chart-bar mr-2"></i>Laporan Lengkap
                    </a>
                </div>
            </div>

            <!-- Quick Stats -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                <div class="bg-white rounded-xl shadow-md p-6 card-hover">
                    <div class="flex justify-between items-center">
                        <div>
                            <p class="text-gray-600">Pendapatan Hari Ini</p>
                            <p class="text-2xl font-bold text-green-600">Rp {{ number_format($todayRevenue, 0, ',', '.') }}</p>
                        </div>
                        <div class="bg-green-100 p-3 rounded-full">
                            <i class="fas fa-coins text-green-600 text-xl"></i>
                        </div>
                    </div>
                    <p class="text-sm text-gray-500 mt-2">{{ $todayOrders }} transaksi</p>
                </div>
                
                <div class="bg-white rounded-xl shadow-md p-6 card-hover">
                    <div class="flex justify-between items-center">
                        <div>
                            <p class="text-gray-600">Total Pelanggan</p>
                            <p class="text-2xl font-bold text-blue-600">{{ $totalCustomers }}</p>
                        </div>
                        <div class="bg-blue-100 p-3 rounded-full">
                            <i class="fas fa-users text-blue-600 text-xl"></i>
                        </div>
                    </div>
                    <p class="text-sm text-gray-500 mt-2">{{ $newCustomersToday }} baru hari ini</p>
                </div>
                
                <div class="bg-white rounded-xl shadow-md p-6 card-hover">
                    <div class="flex justify-between items-center">
                        <div>
                            <p class="text-gray-600">Order Bulan Ini</p>
                            <p class="text-2xl font-bold text-purple-600">{{ $monthOrders }}</p>
                        </div>
                        <div class="bg-purple-100 p-3 rounded-full">
                            <i class="fas fa-receipt text-purple-600 text-xl"></i>
                        </div>
                    </div>
                    <p class="text-sm text-gray-500 mt-2">Rp {{ number_format($monthRevenue, 0, ',', '.') }}</p>
                </div>
                
                <div class="bg-white rounded-xl shadow-md p-6 card-hover">
                    <div class="flex justify-between items-center">
                        <div>
                            <p class="text-gray-600">Staff Aktif</p>
                            <p class="text-2xl font-bold text-orange-600">{{ $activeStaff }} / {{ $totalStaff }}</p>
                        </div>
                        <div class="bg-orange-100 p-3 rounded-full">
                            <i class="fas fa-user-tie text-orange-600 text-xl"></i>
                        </div>
                    </div>
                    <p class="text-sm text-gray-500 mt-2">Sedang bekerja</p>
                </div>
            </div>
        </div>

        <!-- Charts Section -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
            <!-- Daily Revenue Chart -->
            <div class="bg-white rounded-xl shadow-md p-6">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-xl font-bold text-gray-800">
                        <i class="fas fa-chart-line text-blue-600 mr-2"></i>Pendapatan 7 Hari Terakhir
                    </h2>
                    <select id="chartPeriod" class="px-3 py-1 border border-gray-300 rounded-lg text-sm">
                        <option value="7">7 Hari</option>
                        <option value="30">30 Hari</option>
                    </select>
                </div>
                <div class="h-64">
                    <canvas id="revenueChart"></canvas>
                </div>
            </div>

            <!-- Order Status Distribution -->
            <div class="bg-white rounded-xl shadow-md p-6">
                <h2 class="text-xl font-bold text-gray-800 mb-6">
                    <i class="fas fa-chart-pie text-purple-600 mr-2"></i>Distribusi Status Order
                </h2>
                <div class="h-64">
                    <canvas id="statusChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Detailed Stats -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
            <!-- Payment Stats -->
            <div class="bg-white rounded-xl shadow-md p-6">
                <h2 class="text-xl font-bold text-gray-800 mb-6">
                    <i class="fas fa-money-bill-wave text-green-600 mr-2"></i>Statistik Pembayaran
                </h2>
                <div class="space-y-4">
                    <div>
                        <div class="flex justify-between mb-1">
                            <span class="text-gray-600">Sudah Bayar</span>
                            <span class="font-bold text-green-600">Rp {{ number_format($paymentStats['paid'], 0, ',', '.') }}</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-green-500 h-2 rounded-full" 
                                 style="width: {{ $paymentStats['paid'] + $paymentStats['unpaid'] > 0 ? ($paymentStats['paid'] / ($paymentStats['paid'] + $paymentStats['unpaid'])) * 100 : 0 }}%"></div>
                        </div>
                    </div>
                    <div>
                        <div class="flex justify-between mb-1">
                            <span class="text-gray-600">Belum Bayar</span>
                            <span class="font-bold text-red-600">Rp {{ number_format($paymentStats['unpaid'], 0, ',', '.') }}</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-red-500 h-2 rounded-full" 
                                 style="width: {{ $paymentStats['paid'] + $paymentStats['unpaid'] > 0 ? ($paymentStats['unpaid'] / ($paymentStats['paid'] + $paymentStats['unpaid'])) * 100 : 0 }}%"></div>
                        </div>
                    </div>
                    <div class="pt-4 border-t">
                        <div class="grid grid-cols-2 gap-4 text-center">
                            <div class="bg-green-50 p-3 rounded-lg">
                                <p class="text-sm text-gray-600">Lunas</p>
                                <p class="text-lg font-bold text-green-600">
                                    {{ $paymentStats['paid'] > 0 ? round(($paymentStats['paid'] / ($paymentStats['paid'] + $paymentStats['unpaid'])) * 100, 1) : 0 }}%
                                </p>
                            </div>
                            <div class="bg-red-50 p-3 rounded-lg">
                                <p class="text-sm text-gray-600">Belum</p>
                                <p class="text-lg font-bold text-red-600">
                                    {{ $paymentStats['unpaid'] > 0 ? round(($paymentStats['unpaid'] / ($paymentStats['paid'] + $paymentStats['unpaid'])) * 100, 1) : 0 }}%
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Popular Services -->
            <div class="bg-white rounded-xl shadow-md p-6">
                <h2 class="text-xl font-bold text-gray-800 mb-6">
                    <i class="fas fa-star text-yellow-600 mr-2"></i>Layanan Terpopuler
                </h2>
                <div class="space-y-4">
                    @foreach($popularServices as $service)
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                                <i class="fas fa-tshirt text-blue-600"></i>
                            </div>
                            <div>
                                <p class="font-medium">{{ $service->name }}</p>
                                <p class="text-sm text-gray-500">{{ $service->orders_count }} order</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="font-bold text-blue-600">Rp {{ number_format($service->price_per_kg, 0, ',', '.') }}/kg</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Top Customers -->
            <div class="bg-white rounded-xl shadow-md p-6">
                <h2 class="text-xl font-bold text-gray-800 mb-6">
                    <i class="fas fa-crown text-purple-600 mr-2"></i>Pelanggan Terbaik
                </h2>
                <div class="space-y-4">
                    @foreach($topCustomers as $index => $customer)
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                        <div class="flex items-center">
                            <div class="w-10 h-10 {{ $index < 3 ? 'bg-yellow-100' : 'bg-gray-100' }} rounded-full flex items-center justify-center mr-3">
                                <span class="font-bold {{ $index < 3 ? 'text-yellow-600' : 'text-gray-600' }}">#{{ $index + 1 }}</span>
                            </div>
                            <div>
                                <p class="font-medium">{{ $customer->name }}</p>
                                <p class="text-sm text-gray-500">{{ $customer->total_orders }} order</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="font-bold text-green-600">Rp {{ number_format($customer->total_spent, 0, ',', '.') }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Recent Orders & Overdue -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Recent Orders -->
            <div class="bg-white rounded-xl shadow-md p-6">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-xl font-bold text-gray-800">
                        <i class="fas fa-clock text-blue-600 mr-2"></i>Order Terbaru
                    </h2>
                    <a href="{{ route('admin.reports') }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                        Lihat semua →
                    </a>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="border-b">
                                <th class="text-left py-3 text-gray-600 font-medium">Order ID</th>
                                <th class="text-left py-3 text-gray-600 font-medium">Pelanggan</th>
                                <th class="text-left py-3 text-gray-600 font-medium">Total</th>
                                <th class="text-left py-3 text-gray-600 font-medium">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentOrders as $order)
                            <tr class="border-b hover:bg-gray-50">
                                <td class="py-4">
                                    <a href="{{ route('orders.show', $order) }}" class="font-bold text-blue-600 hover:underline">
                                        {{ $order->invoice_number }}
                                    </a>
                                </td>
                                <td class="py-4">
                                    <div class="font-medium">{{ $order->customer->name }}</div>
                                    <div class="text-xs text-gray-500">{{ $order->created_at->format('H:i') }}</div>
                                </td>
                                <td class="py-4 font-bold text-blue-600">
                                    Rp {{ number_format($order->total_price, 0, ',', '.') }}
                                </td>
                                <td class="py-4">
                                    @php
                                        $statusColors = [
                                            'pending' => 'bg-gray-100 text-gray-800',
                                            'cuci' => 'bg-blue-100 text-blue-800',
                                            'kering' => 'bg-yellow-100 text-yellow-800',
                                            'setrika' => 'bg-orange-100 text-orange-800',
                                            'selesai' => 'bg-green-100 text-green-800',
                                            'diambil' => 'bg-purple-100 text-purple-800',
                                            'batal' => 'bg-red-100 text-red-800'
                                        ];
                                    @endphp
                                    <span class="px-3 py-1 rounded-full text-sm font-medium {{ $statusColors[$order->status] ?? 'bg-gray-100 text-gray-800' }}">
                                        {{ ucfirst($order->status) }}
                                    </span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Overdue Orders -->
            <div class="bg-white rounded-xl shadow-md p-6">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-xl font-bold text-gray-800">
                        <i class="fas fa-exclamation-triangle text-red-600 mr-2"></i>Order Terlambat
                    </h2>
                    <span class="bg-red-100 text-red-800 px-3 py-1 rounded-full text-sm font-medium">
                        {{ $overdueOrders->count() }} order
                    </span>
                </div>
                @if($overdueOrders->count() > 0)
                <div class="space-y-4">
                    @foreach($overdueOrders as $order)
                    <div class="p-4 bg-red-50 border border-red-200 rounded-lg">
                        <div class="flex justify-between items-start mb-2">
                            <div>
                                <a href="{{ route('orders.show', $order) }}" class="font-bold text-blue-600 hover:underline">
                                    {{ $order->invoice_number }}
                                </a>
                                <p class="text-sm">{{ $order->customer->name }}</p>
                            </div>
                            <span class="px-2 py-1 bg-red-100 text-red-800 rounded text-xs font-medium">
                                Terlambat
                            </span>
                        </div>
                        <div class="text-sm text-gray-600">
                            <div class="flex justify-between">
                                <span>Estimasi:</span>
                                <span class="font-medium">{{ $order->estimated_finish_at->format('H:i') }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span>Terlambat:</span>
                                <span class="font-bold text-red-600">{{ $order->getRemainingTime() }}</span>
                            </div>
                            <div class="mt-2 flex space-x-2">
                                <a href="{{ route('orders.show', $order) }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                    <i class="fas fa-eye mr-1"></i>Detail
                                </a>
                                <form method="POST" action="{{ route('orders.status', $order) }}" class="inline">
                                    @csrf
                                    <button type="submit" name="status" value="selesai" class="text-green-600 hover:text-green-800 text-sm font-medium">
                                        <i class="fas fa-check mr-1"></i>Tandai Selesai
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                <div class="text-center py-8">
                    <i class="fas fa-check-circle text-4xl text-green-500 mb-2"></i>
                    <p class="text-gray-600">Tidak ada order terlambat</p>
                    <p class="text-sm text-gray-500">Semua order tepat waktu!</p>
                </div>
                @endif
            </div>
        </div>

        <!-- Summary Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-8">
            <div class="bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-xl shadow-md p-6">
                <div class="flex justify-between items-center">
                    <div>
                        <p class="text-blue-100">Order Hari Ini</p>
                        <p class="text-3xl font-bold">{{ $todayOrders }}</p>
                    </div>
                    <i class="fas fa-calendar-day text-3xl opacity-75"></i>
                </div>
                <p class="text-blue-100 text-sm mt-2">{{ $todayCompleted }} selesai • Rp {{ number_format($todayPaid, 0, ',', '.') }} dibayar</p>
            </div>
            
            <div class="bg-gradient-to-r from-green-500 to-green-600 text-white rounded-xl shadow-md p-6">
                <div class="flex justify-between items-center">
                    <div>
                        <p class="text-green-100">Order Minggu Ini</p>
                        <p class="text-3xl font-bold">{{ $weekOrders }}</p>
                    </div>
                    <i class="fas fa-calendar-week text-3xl opacity-75"></i>
                </div>
                <p class="text-green-100 text-sm mt-2">Rp {{ number_format($weekRevenue, 0, ',', '.') }} pendapatan</p>
            </div>
            
            <div class="bg-gradient-to-r from-purple-500 to-purple-600 text-white rounded-xl shadow-md p-6">
                <div class="flex justify-between items-center">
                    <div>
                        <p class="text-purple-100">Order Bulan Ini</p>
                        <p class="text-3xl font-bold">{{ $monthOrders }}</p>
                    </div>
                    <i class="fas fa-calendar-alt text-3xl opacity-75"></i>
                </div>
                <p class="text-purple-100 text-sm mt-2">Rp {{ number_format($monthRevenue, 0, ',', '.') }} pendapatan</p>
            </div>
        </div>
    </div>

    <!-- JavaScript -->
    <script>
        // User menu toggle
        function toggleUserMenu(e) {
            e.stopPropagation();
            const menu = document.getElementById('userMenu');
            const btn = document.getElementById('userMenuButton');
            if (!menu || !btn) return;
            menu.classList.toggle('hidden');
            const expanded = menu.classList.contains('hidden') ? 'false' : 'true';
            btn.setAttribute('aria-expanded', expanded);
        }

        // Close menu when clicking outside
        document.addEventListener('click', function(e) {
            const menu = document.getElementById('userMenu');
            const btn = document.getElementById('userMenuButton');
            const wrapper = document.getElementById('userMenuWrapper');
            if (!menu || !btn || !wrapper) return;
            if (!menu.classList.contains('hidden') && !wrapper.contains(e.target)) {
                menu.classList.add('hidden');
                btn.setAttribute('aria-expanded', 'false');
            }
        });

        // Revenue Chart
        const revenueCtx = document.getElementById('revenueChart').getContext('2d');
        const revenueChart = new Chart(revenueCtx, {
            type: 'bar',
            data: {
                labels: @json(array_column($dailyRevenue, 'day')),
                datasets: [{
                    label: 'Pendapatan',
                    data: @json(array_column($dailyRevenue, 'revenue')),
                    backgroundColor: 'rgba(59, 130, 246, 0.5)',
                    borderColor: 'rgba(59, 130, 246, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return 'Rp ' + value.toLocaleString('id-ID');
                            }
                        }
                    }
                },
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                let label = context.dataset.label || '';
                                if (label) {
                                    label += ': ';
                                }
                                label += 'Rp ' + context.parsed.y.toLocaleString('id-ID');
                                return label;
                            }
                        }
                    }
                }
            }
        });

        // Status Distribution Chart
        const statusCtx = document.getElementById('statusChart').getContext('2d');
        const statusChart = new Chart(statusCtx, {
            type: 'doughnut',
            data: {
                labels: ['Pending', 'Cuci', 'Kering', 'Setrika', 'Selesai', 'Diambil', 'Batal'],
                datasets: [{
                    data: @json(array_values($statusCounts)),
                    backgroundColor: [
                        'rgba(156, 163, 175, 0.7)',    // gray - pending
                        'rgba(59, 130, 246, 0.7)',     // blue - cuci
                        'rgba(245, 158, 11, 0.7)',     // yellow - kering
                        'rgba(249, 115, 22, 0.7)',     // orange - setrika
                        'rgba(16, 185, 129, 0.7)',     // green - selesai
                        'rgba(139, 92, 246, 0.7)',     // purple - diambil
                        'rgba(239, 68, 68, 0.7)'       // red - batal
                    ],
                    borderColor: [
                        'rgb(156, 163, 175)',
                        'rgb(59, 130, 246)',
                        'rgb(245, 158, 11)',
                        'rgb(249, 115, 22)',
                        'rgb(16, 185, 129)',
                        'rgb(139, 92, 246)',
                        'rgb(239, 68, 68)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'right',
                    }
                }
            }
        });

        // Update time
        function updateTime() {
            const now = new Date();
            const timeString = now.toLocaleTimeString('id-ID', { 
                hour12: false,
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit'
            });
            document.getElementById('currentTime').textContent = timeString;
        }
        setInterval(updateTime, 1000);
        updateTime();
    </script>

</body>
</html>