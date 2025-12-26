<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Order | WashFlow</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-50">
    
    <!-- Navbar -->
    <nav class="bg-gradient-to-r from-blue-600 to-indigo-700 text-white shadow-lg">
        <div class="container mx-auto px-4 py-3">
            <div class="flex justify-between items-center">
                <div class="flex items-center space-x-3">
                    <div class="bg-white p-2 rounded-lg">
                        <i class="fas fa-tshirt text-blue-600 text-xl"></i>
                    </div>
                    <div>
                        <h1 class="text-xl font-bold">WashFlow</h1>
                        <p class="text-blue-100 text-xs">Detail Order</p>
                    </div>
                </div>
                <div class="flex items-center space-x-4">
                    <a href="{{ route('dashboard') }}" class="bg-white text-blue-600 px-4 py-2 rounded-lg hover:bg-blue-50">
                        <i class="fas fa-arrow-left mr-2"></i>Kembali ke Dashboard
                    </a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-600">
                            <i class="fas fa-sign-out-alt mr-2"></i>Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container mx-auto px-4 py-8">
        <!-- Alert Success -->
        @if(session('success'))
        <div class="mb-6 bg-green-100 border-l-4 border-green-500 p-4 rounded">
            <div class="flex items-center">
                <i class="fas fa-check-circle text-green-500 text-xl mr-3"></i>
                <div>
                    <p class="font-semibold text-green-800">Berhasil!</p>
                    <p class="text-green-700">{{ session('success') }}</p>
                </div>
            </div>
        </div>
        @endif

        <!-- Header -->
        <div class="bg-white rounded-xl shadow-md p-6 mb-6">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-3xl font-bold text-gray-800">
                        <i class="fas fa-receipt text-blue-600 mr-2"></i>
                        Order: {{ $order->invoice_number }}
                    </h1>
                    <p class="text-gray-600">Tanggal: {{ $order->created_at->format('d/m/Y H:i') }}</p>
                </div>
                <div class="flex space-x-4">
                    <button onclick="window.print()" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                        <i class="fas fa-print mr-2"></i>Cetak Nota
                    </button>
                    @if($order->payment_status == 'unpaid')
                    <form method="POST" action="{{ route('orders.paid', $order) }}">
                        @csrf
                        <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700">
                            <i class="fas fa-money-bill mr-2"></i>Tandai Sudah Bayar
                        </button>
                    </form>
                    @endif
                </div>
            </div>
        </div>

        <!-- Order Details -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left Column: Order Info -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Customer Info -->
                <div class="bg-white rounded-xl shadow-md p-6">
                    <h2 class="text-xl font-bold text-gray-800 mb-4">
                        <i class="fas fa-user text-blue-600 mr-2"></i>Informasi Pelanggan
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <p class="text-gray-600 text-sm">Nama</p>
                            <p class="font-bold text-lg">{{ $order->customer->name }}</p>
                        </div>
                        <div>
                            <p class="text-gray-600 text-sm">Telepon</p>
                            <p class="font-bold text-lg">{{ $order->customer->phone }}</p>
                        </div>
                        <div>
                            <p class="text-gray-600 text-sm">Email</p>
                            <p class="font-bold">{{ $order->customer->email ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="text-gray-600 text-sm">Total Order</p>
                            <p class="font-bold">{{ $order->customer->total_orders }} kali</p>
                        </div>
                        <div class="md:col-span-2">
                            <p class="text-gray-600 text-sm">Alamat</p>
                            <p class="font-bold">{{ $order->customer->address ?? '-' }}</p>
                        </div>
                    </div>
                </div>

                <!-- Order Details -->
                <div class="bg-white rounded-xl shadow-md p-6">
                    <h2 class="text-xl font-bold text-gray-800 mb-4">
                        <i class="fas fa-tshirt text-blue-600 mr-2"></i>Detail Cucian
                    </h2>
                    <div class="space-y-4">
                        <div class="flex justify-between items-center p-4 bg-blue-50 rounded-lg">
                            <div>
                                <p class="text-gray-600">Jenis Layanan</p>
                                <p class="text-xl font-bold">{{ $order->service->name }}</p>
                            </div>
                            <div class="text-right">
                                <p class="text-gray-600">Harga per kg</p>
                                <p class="text-xl font-bold text-blue-600">Rp {{ number_format($order->price_per_kg, 0, ',', '.') }}</p>
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <p class="text-gray-600">Berat</p>
                                <p class="text-2xl font-bold">{{ $order->weight }} kg</p>
                            </div>
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <p class="text-gray-600">Estimasi Selesai</p>
                                <p class="text-xl font-bold">
                                    @if($order->estimated_finish_at)
                                        {{ $order->estimated_finish_at->format('H:i') }}
                                    @else
                                        -
                                    @endif
                                </p>
                            </div>
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <p class="text-gray-600">Status Waktu</p>
                                <p class="text-xl font-bold {{ $order->isOverdue() ? 'text-red-600' : 'text-green-600' }}">
                                    @if($order->status == 'selesai' || $order->status == 'diambil')
                                        Selesai
                                    @elseif($order->isOverdue())
                                        Terlambat
                                    @else
                                        On Time
                                    @endif
                                </p>
                            </div>
                        </div>

                        <!-- Special Notes -->
                        @if($order->special_notes)
                        <div class="bg-yellow-50 border-l-4 border-yellow-500 p-4 rounded">
                            <div class="flex items-center">
                                <i class="fas fa-exclamation-circle text-yellow-600 text-xl mr-3"></i>
                                <div>
                                    <p class="font-semibold text-yellow-800">Catatan Khusus</p>
                                    <p class="text-yellow-700">{{ $order->special_notes }}</p>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Right Column: Status & Actions -->
            <div class="space-y-6">
                <!-- Status Card -->
                <div class="bg-white rounded-xl shadow-md p-6">
                    <h2 class="text-xl font-bold text-gray-800 mb-4">
                        <i class="fas fa-tasks text-blue-600 mr-2"></i>Status Order
                    </h2>
                    
                    <!-- Current Status -->
                    <div class="mb-6">
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
                        <div class="flex items-center justify-between mb-4">
                            <span class="text-gray-600">Status Saat Ini</span>
                            <span class="px-3 py-1 rounded-full text-sm font-medium {{ $statusColors[$order->status] }}">
                                {{ ucfirst($order->status) }}
                            </span>
                        </div>
                        
                        <!-- Status Timeline -->
                        <div class="space-y-2">
                            @php
                                $statuses = ['pending', 'cuci', 'kering', 'setrika', 'selesai', 'diambil'];
                                $currentIndex = array_search($order->status, $statuses);
                            @endphp
                            
                            @foreach($statuses as $index => $status)
                            <div class="flex items-center">
                                <div class="w-8 h-8 rounded-full flex items-center justify-center mr-3
                                    {{ $index <= $currentIndex ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-400' }}">
                                    {{ $index + 1 }}
                                </div>
                                <div class="flex-1">
                                    <p class="font-medium {{ $index <= $currentIndex ? 'text-gray-800' : 'text-gray-400' }}">
                                        {{ ucfirst($status) }}
                                    </p>
                                    @if($log = $order->statusLogs->where('status', $status)->first())
                                    <p class="text-xs text-gray-500">
                                        {{ $log->created_at->format('H:i') }} oleh {{ $log->user->name }}
                                    </p>
                                    @endif
                                </div>
                            </div>
                            @if($index < count($statuses) - 1)
                            <div class="h-4 border-l-2 border-dashed border-gray-300 ml-4"></div>
                            @endif
                            @endforeach
                        </div>
                    </div>

                    <!-- Update Status Form -->
                    <form method="POST" action="{{ route('orders.status', $order) }}">
                        @csrf
                        <div class="mb-4">
                            <label class="block text-gray-700 font-medium mb-2">Update Status</label>
                            <select name="status" class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                                @foreach($statuses as $status)
                                    @if($status != $order->status)
                                    <option value="{{ $status }}">{{ ucfirst($status) }}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-4">
                            <label class="block text-gray-700 font-medium mb-2">Catatan (opsional)</label>
                            <textarea name="notes" rows="2" class="w-full px-4 py-2 border border-gray-300 rounded-lg"></textarea>
                        </div>
                        <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700">
                            <i class="fas fa-sync-alt mr-2"></i>Update Status
                        </button>
                    </form>
                </div>

                <!-- Payment Info -->
                <div class="bg-white rounded-xl shadow-md p-6">
                    <h2 class="text-xl font-bold text-gray-800 mb-4">
                        <i class="fas fa-money-bill text-green-600 mr-2"></i>Informasi Pembayaran
                    </h2>
                    <div class="space-y-4">
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">Total Harga</span>
                            <span class="text-2xl font-bold text-blue-600">Rp {{ number_format($order->total_price, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">Status Pembayaran</span>
                            <span class="px-3 py-1 rounded-full text-sm font-medium {{ $order->payment_status == 'paid' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $order->payment_status == 'paid' ? 'LUNAS' : 'BELUM BAYAR' }}
                            </span>
                        </div>
                        @if($order->payment_status == 'paid')
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">Dibayar</span>
                            <span class="font-bold text-green-600">Rp {{ number_format($order->paid_amount, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">Metode Pembayaran</span>
                            <span class="font-bold">{{ ucfirst($order->payment_method) }}</span>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="bg-white rounded-xl shadow-md p-6">
                    <h2 class="text-xl font-bold text-gray-800 mb-4">
                        <i class="fas fa-bolt text-yellow-600 mr-2"></i>Aksi Cepat
                    </h2>
                    <div class="space-y-3">
                        <a href="{{ route('dashboard') }}" class="w-full bg-blue-600 text-white py-3 rounded-lg font-bold hover:bg-blue-700 block text-center">
                            <i class="fas fa-plus-circle mr-2"></i>Buat Order Baru
                        </a>
                        <button onclick="window.print()" class="w-full bg-gray-600 text-white py-3 rounded-lg font-bold hover:bg-gray-700">
                            <i class="fas fa-print mr-2"></i>Cetak Nota
                        </button>
                        @if($order->payment_status == 'unpaid')
                        <form method="POST" action="{{ route('orders.paid', $order) }}">
                            @csrf
                            <button type="submit" class="w-full bg-green-600 text-white py-3 rounded-lg font-bold hover:bg-green-700">
                                <i class="fas fa-money-bill mr-2"></i>Tandai Sudah Dibayar
                            </button>
                        </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Status Logs -->
        @if($order->statusLogs->count() > 0)
        <div class="mt-8 bg-white rounded-xl shadow-md p-6">
            <h2 class="text-xl font-bold text-gray-800 mb-4">
                <i class="fas fa-history text-blue-600 mr-2"></i>Riwayat Perubahan Status
            </h2>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b">
                            <th class="text-left py-3 text-gray-600 font-medium">Waktu</th>
                            <th class="text-left py-3 text-gray-600 font-medium">Status</th>
                            <th class="text-left py-3 text-gray-600 font-medium">Diubah Oleh</th>
                            <th class="text-left py-3 text-gray-600 font-medium">Catatan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($order->statusLogs->sortByDesc('created_at') as $log)
                        <tr class="border-b hover:bg-gray-50">
                            <td class="py-4">
                                <div class="font-medium">{{ $log->created_at->format('d/m/Y') }}</div>
                                <div class="text-sm text-gray-500">{{ $log->created_at->format('H:i:s') }}</div>
                            </td>
                            <td class="py-4">
                                <span class="px-3 py-1 rounded-full text-sm font-medium {{ $statusColors[$log->status] ?? 'bg-gray-100 text-gray-800' }}">
                                    {{ ucfirst($log->status) }}
                                </span>
                            </td>
                            <td class="py-4">
                                <div class="font-medium">{{ $log->user->name ?? 'System' }}</div>
                                <div class="text-sm text-gray-500">{{ $log->user->role ?? '-' }}</div>
                            </td>
                            <td class="py-4">
                                {{ $log->notes ?? '-' }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif
    </div>

    <!-- Print Styles -->
    <style media="print">
        @media print {
            nav, button, form, .no-print {
                display: none !important;
            }
            body {
                background: white !important;
            }
            .container {
                max-width: 100% !important;
                padding: 0 !important;
            }
            .bg-white {
                box-shadow: none !important;
                border: 1px solid #e5e7eb !important;
            }
        }
    </style>

</body>
</html>