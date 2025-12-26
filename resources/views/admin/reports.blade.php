<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Lengkap | WashFlow Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-gray-50">
    <!-- Navbar -->
    <nav class="bg-gradient-to-r from-blue-600 to-indigo-700 text-white shadow-lg">
        <div class="container mx-auto px-4 py-3">
            <div class="flex justify-between items-center">
                <div class="flex items-center space-x-3">
                    <a href="{{ route('admin.dashboard') }}" class="bg-white p-2 rounded-lg">
                        <i class="fas fa-arrow-left text-blue-600 text-xl"></i>
                    </a>
                    <div>
                        <h1 class="text-xl font-bold">Laporan Lengkap</h1>
                        <p class="text-blue-100 text-xs">Analisis & Riwayat Transaksi</p>
                    </div>
                </div>
                <div class="flex items-center space-x-4">
                    <span class="font-medium">{{ auth()->user()->name }}</span>
                    <div class="w-10 h-10 bg-white rounded-full flex items-center justify-center">
                        <i class="fas fa-user text-blue-600"></i>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <div class="container mx-auto px-4 py-8">
        <!-- Filters -->
        <div class="bg-white rounded-xl shadow-md p-6 mb-8">
            <form action="{{ route('admin.reports') }}" method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Periode Waktu</label>
                    <select name="range" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500 py-2 px-3 border">
                        <option value="today" {{ $dateRange == 'today' ? 'selected' : '' }}>Hari Ini</option>
                        <option value="week" {{ $dateRange == 'week' ? 'selected' : '' }}>Minggu Ini</option>
                        <option value="month" {{ $dateRange == 'month' ? 'selected' : '' }}>Bulan Ini</option>
                        <option value="all" {{ $dateRange == 'all' ? 'selected' : '' }}>Semua Waktu</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Status Order</label>
                    <select name="status" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500 py-2 px-3 border">
                        <option value="all" {{ $status == 'all' ? 'selected' : '' }}>Semua Status</option>
                        <option value="pending" {{ $status == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="cuci" {{ $status == 'cuci' ? 'selected' : '' }}>Proses Cuci</option>
                        <option value="selesai" {{ $status == 'selesai' ? 'selected' : '' }}>Selesai</option>
                        <option value="diambil" {{ $status == 'diambil' ? 'selected' : '' }}>Sudah Diambil</option>
                    </select>
                </div>
                <div class="flex items-end">
                    <button type="submit" class="w-full bg-blue-600 text-white font-bold py-2 px-4 rounded-lg hover:bg-blue-700 transition duration-200">
                        <i class="fas fa-filter mr-2"></i>Terapkan Filter
                    </button>
                </div>
            </form>
        </div>

        <!-- Table -->
        <div class="bg-white rounded-xl shadow-md overflow-hidden">
            <div class="p-6 border-b border-gray-200 flex justify-between items-center">
                <h2 class="text-xl font-bold text-gray-800">Riwayat Transaksi</h2>
                <button onclick="window.print()" class="text-gray-600 hover:text-blue-600">
                    <i class="fas fa-print mr-1"></i> Cetak Halaman
                </button>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50">
                            <th class="px-6 py-4 text-sm font-semibold text-gray-600 uppercase tracking-wider">No. Invoice</th>
                            <th class="px-6 py-4 text-sm font-semibold text-gray-600 uppercase tracking-wider">Pelanggan</th>
                            <th class="px-6 py-4 text-sm font-semibold text-gray-600 uppercase tracking-wider">Tanggal</th>
                            <th class="px-6 py-4 text-sm font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-4 text-sm font-semibold text-gray-600 uppercase tracking-wider">Total Harga</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($orders as $order)
                        <tr class="hover:bg-gray-50 transition duration-150">
                            <td class="px-6 py-4 font-bold text-blue-600">{{ $order->invoice_number }}</td>
                            <td class="px-6 py-4">
                                <div class="text-sm font-medium text-gray-900">{{ $order->customer->name }}</div>
                                <div class="text-xs text-gray-500">{{ $order->customer->phone }}</div>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500">
                                {{ $order->created_at->format('d M Y, H:i') }}
                            </td>
                            <td class="px-6 py-4">
                                @php
                                    $statusColors = [
                                        'pending' => 'bg-gray-100 text-gray-800',
                                        'cuci' => 'bg-blue-100 text-blue-800',
                                        'selesai' => 'bg-green-100 text-green-800',
                                        'diambil' => 'bg-purple-100 text-purple-800',
                                        'batal' => 'bg-red-100 text-red-800'
                                    ];
                                @endphp
                                <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusColors[$order->status] ?? 'bg-gray-100 text-gray-800' }}">
                                    {{ ucfirst($order->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm font-bold text-gray-900">
                                Rp {{ number_format($order->total_price, 0, ',', '.') }}
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-6 py-10 text-center text-gray-500 italic">
                                Tidak ada data ditemukan untuk filter ini.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($orders->hasPages())
            <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
                {{ $orders->links() }}
            </div>
            @endif
        </div>
    </div>
</body>
</html>
