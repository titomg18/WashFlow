<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Pelanggan | WashFlow</title>
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
<body class="bg-gray-50">
    
    <!-- Navbar Kasir (SAMA DENGAN DASHBOARD) -->
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
                        <p class="text-blue-100 text-xs">Detail Pelanggan</p>
                    </div>
                </div>

                <!-- Info Kasir & Shift -->
                <div class="flex items-center space-x-6">
                    <div class="text-center">
                        <p class="text-xs text-blue-200">Kasir</p>
                        <p class="font-bold">{{ auth()->user()->name }}</p>
                    </div>
                    <div class="text-center">
                        <p class="text-xs text-blue-200">Shift</p>
                        <p class="font-bold">{{ date('d/m/Y') }}</p>
                    </div>
                    <div class="text-center">
                        <p class="text-xs text-blue-200">Jam</p>
                        <p class="font-bold" id="currentTime">{{ date('H:i:s') }}</p>
                    </div>
                </div>

                <!-- User Menu -->
                <div class="flex items-center space-x-4">
                    <!-- Tombol Kembali ke Dashboard -->
                    <a href="{{ route('dashboard') }}" class="bg-white text-blue-600 px-4 py-2 rounded-lg hover:bg-blue-50 font-medium">
                        <i class="fas fa-arrow-left mr-2"></i>Dashboard
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
                                <p class="text-xs text-gray-500">{{ auth()->user()->role }}</p>
                            </div>
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
        <!-- Customer Info Card -->
        <div class="bg-white rounded-xl shadow-md p-6 mb-6">
            <div class="flex flex-col md:flex-row md:items-center justify-between mb-6">
                <div class="flex items-center space-x-4 mb-4 md:mb-0">
                    <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-user text-blue-600 text-2xl"></i>
                    </div>
                    <div>
                        <h1 class="text-3xl font-bold text-gray-800">{{ $customer->name }}</h1>
                        <p class="text-gray-600">{{ $customer->code }}</p>
                    </div>
                </div>
                <div class="flex flex-wrap gap-3">
                    <a href="{{ route('dashboard') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 font-medium">
                        <i class="fas fa-plus-circle mr-2"></i>Order Baru
                    </a>
                    <button onclick="window.print()" class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 font-medium">
                        <i class="fas fa-print mr-2"></i>Cetak
                    </button>
                </div>
            </div>

            <!-- Customer Stats -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                <div class="bg-blue-50 p-4 rounded-lg">
                    <p class="text-gray-600 text-sm">Total Order</p>
                    <p class="text-2xl font-bold text-blue-600">{{ $stats['total_orders'] }}</p>
                </div>
                <div class="bg-green-50 p-4 rounded-lg">
                    <p class="text-gray-600 text-sm">Total Pengeluaran</p>
                    <p class="text-2xl font-bold text-green-600">Rp {{ number_format($stats['total_spent'], 0, ',', '.') }}</p>
                </div>
                <div class="bg-purple-50 p-4 rounded-lg">
                    <p class="text-gray-600 text-sm">Rata-rata per Order</p>
                    <p class="text-2xl font-bold text-purple-600">Rp {{ number_format($stats['avg_spent'], 0, ',', '.') }}</p>
                </div>
                <div class="bg-yellow-50 p-4 rounded-lg">
                    <p class="text-gray-600 text-sm">Order Terakhir</p>
                    <p class="text-lg font-bold text-yellow-600">
                        @if($stats['last_order'])
                            {{ $stats['last_order']->created_at->format('d/m/Y') }}
                        @else
                            -
                        @endif
                    </p>
                </div>
            </div>

            <!-- Customer Details -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <h3 class="text-lg font-semibold text-gray-800 mb-3">
                        <i class="fas fa-address-card text-blue-600 mr-2"></i>Informasi Kontak
                    </h3>
                    <div class="space-y-3">
                        <div>
                            <p class="text-gray-600 text-sm">Telepon</p>
                            <p class="font-medium">{{ $customer->phone ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="text-gray-600 text-sm">Email</p>
                            <p class="font-medium">{{ $customer->email ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="text-gray-600 text-sm">Alamat</p>
                            <p class="font-medium">{{ $customer->address ?? '-' }}</p>
                        </div>
                    </div>
                </div>
                
                <div>
                    <h3 class="text-lg font-semibold text-gray-800 mb-3">
                        <i class="fas fa-sticky-note text-blue-600 mr-2"></i>Catatan
                    </h3>
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <p class="text-gray-700">{{ $customer->notes ?? 'Tidak ada catatan' }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Orders History -->
        <div class="bg-white rounded-xl shadow-md p-6">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold text-gray-800">
                    <i class="fas fa-history text-blue-600 mr-2"></i>Riwayat Order
                </h2>
                <p class="text-gray-600">
                    Menampilkan {{ $orders->count() }} dari {{ $stats['total_orders'] }} order
                </p>
            </div>

            @if($orders->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b">
                            <th class="text-left py-3 text-gray-600 font-medium">No. Order</th>
                            <th class="text-left py-3 text-gray-600 font-medium">Tanggal</th>
                            <th class="text-left py-3 text-gray-600 font-medium">Layanan</th>
                            <th class="text-left py-3 text-gray-600 font-medium">Berat</th>
                            <th class="text-left py-3 text-gray-600 font-medium">Total</th>
                            <th class="text-left py-3 text-gray-600 font-medium">Status</th>
                            <th class="text-left py-3 text-gray-600 font-medium">Pembayaran</th>
                            <th class="text-left py-3 text-gray-600 font-medium">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($orders as $order)
                        <tr class="border-b hover:bg-gray-50">
                            <td class="py-4">
                                <div class="font-bold text-blue-600">{{ $order->invoice_number }}</div>
                            </td>
                            <td class="py-4">
                                <div class="font-medium">{{ $order->created_at->format('d/m/Y') }}</div>
                                <div class="text-sm text-gray-500">{{ $order->created_at->format('H:i') }}</div>
                            </td>
                            <td class="py-4">
                                <div class="font-medium">{{ $order->service->name }}</div>
                                <div class="text-xs text-gray-500">
                                    Rp {{ number_format($order->price_per_kg, 0, ',', '.') }}/kg
                                </div>
                            </td>
                            <td class="py-4">
                                <div class="font-medium">{{ $order->weight }} kg</div>
                            </td>
                            <td class="py-4">
                                <div class="font-bold text-blue-600">Rp {{ number_format($order->total_price, 0, ',', '.') }}</div>
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
                            <td class="py-4">
                                @if($order->payment_status == 'paid')
                                <span class="px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                    <i class="fas fa-check mr-1"></i>Lunas
                                </span>
                                @else
                                <span class="px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">
                                    <i class="fas fa-clock mr-1"></i>Belum
                                </span>
                                @endif
                            </td>
                            <td class="py-4">
                                <a href="{{ route('orders.show', $order) }}" 
                                   class="bg-blue-100 text-blue-800 px-3 py-1 rounded text-sm hover:bg-blue-200 inline-block">
                                    <i class="fas fa-eye mr-1"></i>Detail
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                
                <!-- Pagination -->
                <div class="mt-6">
                    {{ $orders->links() }}
                </div>
            </div>
            @else
            <div class="text-center py-8">
                <i class="fas fa-inbox text-4xl text-gray-400 mb-2"></i>
                <p class="text-gray-600">Pelanggan ini belum memiliki riwayat order</p>
                <a href="{{ route('dashboard') }}" class="mt-4 inline-block bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                    <i class="fas fa-plus-circle mr-2"></i>Buat Order Pertama
                </a>
            </div>
            @endif
        </div>
    </div>

    <!-- JavaScript -->
    <script>
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

        // Close menu when clicking outside or pressing Escape
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

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                const menu = document.getElementById('userMenu');
                const btn = document.getElementById('userMenuButton');
                if (menu && !menu.classList.contains('hidden')) {
                    menu.classList.add('hidden');
                    if (btn) btn.setAttribute('aria-expanded', 'false');
                }
            }
        });

        // Initialize
        updateTime();
    </script>

</body>
</html>