<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Kasir | WashFlow - Sistem Manajemen Laundry</title>
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
        .tab-active {
            border-bottom: 3px solid #3b82f6;
            color: #3b82f6;
            font-weight: 600;
        }
        .status-badge {
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 500;
        }
        .status-pending { background-color: #f3f4f6; color: #374151; }
        .status-cuci { background-color: #dbeafe; color: #1d4ed8; }
        .status-kering { background-color: #fef3c7; color: #d97706; }
        .status-setrika { background-color: #ffedd5; color: #ea580c; }
        .status-selesai { background-color: #d1fae5; color: #065f46; }
        .status-diambil { background-color: #ede9fe; color: #5b21b6; }
        .status-batal { background-color: #fee2e2; color: #991b1b; }
    </style>
</head>
<body class="bg-gray-50">
    
    <!-- Navbar Kasir -->
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
                        <p class="text-blue-100 text-xs">Laundry Management System</p>
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
                    <!-- Notifications -->
                    <button class="relative p-2 hover:bg-blue-700 rounded-lg transition" onclick="showNotifications()">
                        <i class="fas fa-bell text-xl"></i>
                        @if($overdueOrders->count() > 0)
                        <span class="absolute top-1 right-1 w-4 h-4 bg-red-500 rounded-full text-xs flex items-center justify-center text-white">
                            {{ $overdueOrders->count() }}
                        </span>
                        @endif
                    </button>
                    
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
        <!-- Welcome & Stats -->
        <div class="mb-8">
            <div class="flex justify-between items-center mb-4">
                <div>
                    <h1 class="text-3xl font-bold text-gray-800">
                        Selamat datang, <span class="text-blue-600">{{ auth()->user()->name }}</span>!
                    </h1>
                    <p class="text-gray-600">Kelola penerimaan cucian dan pantau status laundry</p>
                </div>
                <div class="flex space-x-4">
                    <div class="bg-green-100 text-green-800 px-4 py-2 rounded-lg font-bold">
                        <i class="fas fa-coins mr-2"></i>Hari ini: Rp {{ number_format($todayRevenue, 0, ',', '.') }}
                    </div>
                    <div class="bg-blue-100 text-blue-800 px-4 py-2 rounded-lg font-bold">
                        <i class="fas fa-receipt mr-2"></i>{{ $todayOrders }} Transaksi
                    </div>
                    <div class="bg-purple-100 text-purple-800 px-4 py-2 rounded-lg font-bold">
                        <i class="fas fa-wallet mr-2"></i>Dibayar: Rp {{ number_format($todayPaid, 0, ',', '.') }}
                    </div>
                </div>
            </div>

            <!-- Tabs Navigation -->
            <div class="border-b border-gray-200">
                <nav class="-mb-px flex space-x-8">
                    <button onclick="showTab('penerimaan')" 
                            class="tab-button py-4 px-1 text-sm font-medium text-gray-500 hover:text-gray-700 hover:border-gray-300 transition tab-active"
                            id="tab-penerimaan">
                        <i class="fas fa-plus-circle mr-2"></i>Penerimaan Cucian
                    </button>
                    <button onclick="showTab('status')" 
                            class="tab-button py-4 px-1 text-sm font-medium text-gray-500 hover:text-gray-700 hover:border-gray-300 transition"
                            id="tab-status">
                        <i class="fas fa-tasks mr-2"></i>Status Cucian
                    </button>
                    <button onclick="showTab('riwayat')" 
                            class="tab-button py-4 px-1 text-sm font-medium text-gray-500 hover:text-gray-700 hover:border-gray-300 transition"
                            id="tab-riwayat">
                        <i class="fas fa-history mr-2"></i>Riwayat Pelanggan
                    </button>
                </nav>
            </div>
        </div>

        <!-- Tab Content -->
        <div id="tab-content">
            
            <!-- Tab 1: Penerimaan Cucian -->
            <div id="penerimaan-tab" class="tab-pane">
                <form id="orderForm" method="POST" action="{{ route('orders.store') }}">
                    @csrf
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                        <!-- Left Column: Form Input -->
                        <div class="lg:col-span-2 space-y-6">
                            <!-- Form Pelanggan -->
                            <div class="bg-white rounded-xl shadow-md p-6">
                                <h2 class="text-xl font-bold text-gray-800 mb-4">
                                    <i class="fas fa-user text-blue-600 mr-2"></i>Data Pelanggan
                                </h2>
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-gray-700 font-medium mb-2">Nama Pelanggan *</label>
                                        <div class="relative">
                                            <select id="customer_id" name="customer_id" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                                                <option value="">Pilih Pelanggan</option>
                                                @foreach(App\Models\Customer::orderBy('name')->get() as $customer)
                                                <option value="{{ $customer->id }}">
                                                    {{ $customer->name }} ({{ $customer->phone }})
                                                </option>
                                                @endforeach
                                            </select>
                                            <button type="button" onclick="showAddCustomerModal()" class="absolute right-2 top-2 text-blue-600 hover:text-blue-800">
                                                <i class="fas fa-plus-circle"></i>
                                            </button>
                                        </div>
                                        @error('customer_id')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    
                                    <div>
                                        <label class="block text-gray-700 font-medium mb-2">Pelanggan Baru?</label>
                                        <button type="button" onclick="showAddCustomerModal()" class="w-full bg-blue-50 text-blue-700 border border-blue-200 px-4 py-3 rounded-lg hover:bg-blue-100 transition">
                                            <i class="fas fa-user-plus mr-2"></i>Tambah Pelanggan Baru
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- Form Penerimaan Cucian -->
                            <div class="bg-white rounded-xl shadow-md p-6">
                                <h2 class="text-xl font-bold text-gray-800 mb-4">
                                    <i class="fas fa-tshirt text-blue-600 mr-2"></i>Detail Cucian
                                </h2>
                                
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                                    <div>
                                        <label class="block text-gray-700 font-medium mb-2">Jenis Layanan *</label>
                                        <select id="service_id" name="service_id" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required onchange="calculatePrice()">
                                            <option value="">Pilih Layanan</option>
                                            @foreach(App\Models\Service::where('is_active', true)->orderBy('order')->get() as $service)
                                            <option value="{{ $service->id }}" data-price="{{ $service->price_per_kg }}" data-hours="{{ $service->estimated_hours }}">
                                                {{ $service->name }} (Rp {{ number_format($service->price_per_kg, 0, ',', '.') }}/kg)
                                            </option>
                                            @endforeach
                                        </select>
                                        @error('service_id')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    
                                    <div>
                                        <label class="block text-gray-700 font-medium mb-2">Berat (kg) *</label>
                                        <input type="number" id="weight" name="weight" min="0.5" step="0.5" 
                                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                               placeholder="0.5" required oninput="calculatePrice()">
                                        @error('weight')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    
                                    <div>
                                        <label class="block text-gray-700 font-medium mb-2">Total Harga</label>
                                        <div class="relative">
                                            <div class="absolute left-0 pl-3 flex items-center h-full">
                                                <span class="text-gray-500">Rp</span>
                                            </div>
                                            <input type="text" id="total_price" readonly
                                                   class="w-full pl-10 pr-4 py-3 border border-gray-300 bg-gray-50 rounded-lg"
                                                   value="0">
                                            <input type="hidden" id="price_per_kg" name="price_per_kg" value="0">
                                            <input type="hidden" id="total_price_hidden" name="total_price" value="0">
                                        </div>
                                    </div>
                                </div>

                                <!-- Catatan Khusus -->
                                <div class="mb-6">
                                    <label class="block text-gray-700 font-medium mb-2">Catatan Khusus</label>
                                    <textarea id="special_notes" name="special_notes" 
                                              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                              rows="2"
                                              placeholder="Contoh: Sabun khusus, noda anggur, dll."></textarea>
                                    @error('special_notes')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Estimasi Waktu & Status Pembayaran -->
                                <div class="bg-blue-50 p-4 rounded-lg">
                                    <div class="flex justify-between items-center mb-4">
                                        <div>
                                            <p class="text-gray-600 text-sm">Estimasi Selesai</p>
                                            <p class="text-xl font-bold text-blue-600" id="estimatedFinish">-</p>
                                        </div>
                                        <div>
                                            <p class="text-gray-600 text-sm">Total</p>
                                            <p class="text-2xl font-bold text-blue-600" id="totalPriceDisplay">Rp 0</p>
                                        </div>
                                    </div>
                                    
                                    <div>
                                        <label class="block text-gray-700 font-medium mb-2">Status Pembayaran *</label>
                                        <div class="flex space-x-4">
                                            <label class="inline-flex items-center">
                                                <input type="radio" name="payment_status" value="paid" class="h-5 w-5 text-blue-600" checked onchange="togglePaymentFields()">
                                                <span class="ml-2 text-gray-700">Lunas (Bayar Sekarang)</span>
                                            </label>
                                            <label class="inline-flex items-center">
                                                <input type="radio" name="payment_status" value="unpaid" class="h-5 w-5 text-blue-600" onchange="togglePaymentFields()">
                                                <span class="ml-2 text-gray-700">Belum Bayar</span>
                                            </label>
                                        </div>
                                        @error('payment_status')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Right Column: Aksi & Preview -->
                        <div class="space-y-6">
                            <!-- Panel Aksi -->
                            <div class="bg-white rounded-xl shadow-md p-6">
                                <h2 class="text-xl font-bold text-gray-800 mb-4">
                                    <i class="fas fa-bolt text-yellow-600 mr-2"></i>Aksi Cepat
                                </h2>
                                
                                <div class="space-y-4">
                                    <button type="submit" id="submitBtn"
                                            class="w-full bg-gradient-to-r from-green-600 to-emerald-600 text-white py-3 rounded-lg font-bold hover:from-green-700 hover:to-emerald-700 transition">
                                        <i class="fas fa-check-circle mr-2"></i>Simpan Order
                                    </button>
                                    
                                    <button type="button" onclick="clearForm()" 
                                            class="w-full bg-gray-200 text-gray-800 py-3 rounded-lg font-medium hover:bg-gray-300 transition">
                                        <i class="fas fa-times mr-2"></i>Bersihkan Form
                                    </button>
                                </div>
                                
                                <!-- Alerts -->
                                @if(session('success'))
                                <div class="mt-4 p-3 bg-green-100 text-green-800 rounded-lg text-sm">
                                    <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
                                </div>
                                @endif
                                
                                @if($errors->any())
                                <div class="mt-4 p-3 bg-red-100 text-red-800 rounded-lg text-sm">
                                    <i class="fas fa-exclamation-circle mr-2"></i>Ada kesalahan dalam pengisian form
                                </div>
                                @endif
                            </div>

                            <!-- Preview Nota -->
                            <div class="bg-white rounded-xl shadow-md p-6">
                                <h2 class="text-xl font-bold text-gray-800 mb-4">
                                    <i class="fas fa-receipt text-purple-600 mr-2"></i>Preview Nota
                                </h2>
                                
                                <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                                    <!-- Header Nota -->
                                    <div class="text-center mb-4">
                                        <h3 class="font-bold text-lg">NOTA PENERIMAAN</h3>
                                        <p class="text-sm text-gray-600">WashFlow Laundry</p>
                                        <p class="text-xs text-gray-500">{{ date('d/m/Y H:i:s') }}</p>
                                    </div>
                                    
                                    <!-- Detail -->
                                    <div class="text-xs space-y-2">
                                        <div class="flex justify-between">
                                            <span class="text-gray-600">No. Order:</span>
                                            <span id="previewInvoice">WF-{{ date('Ymd') }}-001</span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span class="text-gray-600">Pelanggan:</span>
                                            <span id="previewCustomer">-</span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span class="text-gray-600">Layanan:</span>
                                            <span id="previewService">-</span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span class="text-gray-600">Berat:</span>
                                            <span id="previewWeight">-</span>
                                        </div>
                                        <div class="border-t my-2"></div>
                                        <div class="flex justify-between font-bold">
                                            <span>TOTAL:</span>
                                            <span id="previewTotal">Rp 0</span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span>Estimasi:</span>
                                            <span id="previewEstimate">-</span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span>Status Bayar:</span>
                                            <span id="previewPayment" class="font-medium">LUNAS</span>
                                        </div>
                                    </div>
                                    
                                    <!-- Footer -->
                                    <div class="text-center mt-4 pt-4 border-t">
                                        <p class="text-xs text-gray-500">Nota ini akan dicetak setelah disimpan</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Statistik Cepat -->
                            <div class="bg-white rounded-xl shadow-md p-6">
                                <h2 class="text-xl font-bold text-gray-800 mb-4">
                                    <i class="fas fa-chart-line text-blue-600 mr-2"></i>Statistik Status
                                </h2>
                                <div class="space-y-3">
                                    <div class="flex justify-between items-center">
                                        <span class="text-gray-600">Pending</span>
                                        <span class="font-bold">{{ $statusCounts['pending'] }}</span>
                                    </div>
                                    <div class="flex justify-between items-center">
                                        <span class="text-gray-600">Cuci</span>
                                        <span class="font-bold">{{ $statusCounts['cuci'] }}</span>
                                    </div>
                                    <div class="flex justify-between items-center">
                                        <span class="text-gray-600">Kering</span>
                                        <span class="font-bold">{{ $statusCounts['kering'] }}</span>
                                    </div>
                                    <div class="flex justify-between items-center">
                                        <span class="text-gray-600">Setrika</span>
                                        <span class="font-bold">{{ $statusCounts['setrika'] }}</span>
                                    </div>
                                    <div class="flex justify-between items-center">
                                        <span class="text-gray-600">Selesai</span>
                                        <span class="font-bold">{{ $statusCounts['selesai'] }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Tab 2: Status Cucian -->
            <div id="status-tab" class="tab-pane hidden">
                <div class="bg-white rounded-xl shadow-md p-6">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-2xl font-bold text-gray-800">
                            <i class="fas fa-tasks text-blue-600 mr-2"></i>Manajemen Status Cucian
                        </h2>
                        <div class="flex space-x-4">
                            <select id="filterStatus" class="px-4 py-2 border border-gray-300 rounded-lg" onchange="filterOrders()">
                                <option value="all">Semua Status</option>
                                <option value="pending">Pending</option>
                                <option value="cuci">Cuci</option>
                                <option value="kering">Kering</option>
                                <option value="setrika">Setrika</option>
                                <option value="selesai">Selesai</option>
                                <option value="diambil">Diambil</option>
                                <option value="batal">Batal</option>
                            </select>
                            <button onclick="refreshOrders()" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                                <i class="fas fa-sync-alt mr-2"></i>Refresh
                            </button>
                        </div>
                    </div>

                    <!-- Stats Cards -->
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                        <div class="bg-blue-50 border-l-4 border-blue-500 p-6 rounded-lg">
                            <div class="flex justify-between items-center">
                                <div>
                                    <p class="text-gray-600">Cuci</p>
                                    <p class="text-3xl font-bold text-gray-800">{{ $statusCounts['cuci'] }}</p>
                                </div>
                                <div class="bg-blue-100 p-3 rounded-full">
                                    <i class="fas fa-soap text-blue-600 text-xl"></i>
                                </div>
                            </div>
                        </div>
                        
                        <div class="bg-yellow-50 border-l-4 border-yellow-500 p-6 rounded-lg">
                            <div class="flex justify-between items-center">
                                <div>
                                    <p class="text-gray-600">Kering</p>
                                    <p class="text-3xl font-bold text-gray-800">{{ $statusCounts['kering'] }}</p>
                                </div>
                                <div class="bg-yellow-100 p-3 rounded-full">
                                    <i class="fas fa-wind text-yellow-600 text-xl"></i>
                                </div>
                            </div>
                        </div>
                        
                        <div class="bg-green-50 border-l-4 border-green-500 p-6 rounded-lg">
                            <div class="flex justify-between items-center">
                                <div>
                                    <p class="text-gray-600">Selesai</p>
                                    <p class="text-3xl font-bold text-gray-800">{{ $statusCounts['selesai'] }}</p>
                                </div>
                                <div class="bg-green-100 p-3 rounded-full">
                                    <i class="fas fa-check-circle text-green-600 text-xl"></i>
                                </div>
                            </div>
                        </div>
                        
                        <div class="bg-purple-50 border-l-4 border-purple-500 p-6 rounded-lg">
                            <div class="flex justify-between items-center">
                                <div>
                                    <p class="text-gray-600">Diambil</p>
                                    <p class="text-3xl font-bold text-gray-800">{{ $statusCounts['diambil'] }}</p>
                                </div>
                                <div class="bg-purple-100 p-3 rounded-full">
                                    <i class="fas fa-truck text-purple-600 text-xl"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Orders Telat -->
                    @if($overdueOrders->count() > 0)
                    <div class="mb-6 bg-red-50 border border-red-200 rounded-lg p-4">
                        <div class="flex items-center mb-2">
                            <i class="fas fa-exclamation-triangle text-red-600 mr-2"></i>
                            <h3 class="font-bold text-red-800">Order Terlambat ({{ $overdueOrders->count() }})</h3>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
                            @foreach($overdueOrders as $order)
                            <div class="bg-white p-3 rounded border border-red-300">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <p class="font-bold text-blue-600">{{ $order->invoice_number }}</p>
                                        <p class="text-sm">{{ $order->customer->name }}</p>
                                    </div>
                                    <span class="status-badge status-pending">Terlambat</span>
                                </div>
                                <p class="text-xs text-gray-600 mt-1">
                                    Estimasi: {{ $order->estimated_finish_at->format('H:i') }}
                                    • {{ $order->getRemainingTime() }} terlambat
                                </p>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    <!-- Tabel Status Cucian -->
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr class="border-b">
                                    <th class="text-left py-3 text-gray-600 font-medium">No. Order</th>
                                    <th class="text-left py-3 text-gray-600 font-medium">Pelanggan</th>
                                    <th class="text-left py-3 text-gray-600 font-medium">Layanan</th>
                                    <th class="text-left py-3 text-gray-600 font-medium">Status</th>
                                    <th class="text-left py-3 text-gray-600 font-medium">Estimasi Selesai</th>
                                    <th class="text-left py-3 text-gray-600 font-medium">Pembayaran</th>
                                    <th class="text-left py-3 text-gray-600 font-medium">Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="ordersTableBody">
                                @foreach($recentOrders as $order)
                                <tr class="border-b hover:bg-gray-50" data-status="{{ $order->status }}">
                                    <td class="py-4">
                                        <div class="font-bold text-blue-600">{{ $order->invoice_number }}</div>
                                        <div class="text-xs text-gray-500">{{ $order->created_at->format('H:i') }}</div>
                                    </td>
                                    <td class="py-4">
                                        <div class="font-medium">{{ $order->customer->name }}</div>
                                        <div class="text-xs text-gray-500">{{ $order->customer->phone }}</div>
                                    </td>
                                    <td class="py-4">
                                        <div class="font-medium">{{ $order->service->name }}</div>
                                        <div class="text-xs text-gray-500">
                                            {{ $order->weight }} kg • Rp {{ number_format($order->total_price, 0, ',', '.') }}
                                        </div>
                                    </td>
                                    <td class="py-4">
                                        <div class="flex items-center">
                                            @php
                                                $icons = [
                                                    'pending' => '⏳',
                                                    'cuci' => '🧼',
                                                    'kering' => '🌬️',
                                                    'setrika' => '🔥',
                                                    'selesai' => '✅',
                                                    'diambil' => '📦',
                                                    'batal' => '❌'
                                                ];
                                            @endphp
                                            <span class="text-xl mr-2">{{ $icons[$order->status] ?? '📝' }}</span>
                                            <span class="status-badge status-{{ $order->status }}">
                                                {{ ucfirst($order->status) }}
                                            </span>
                                        </div>
                                    </td>
                                    <td class="py-4">
                                        @if($order->estimated_finish_at)
                                        <div class="font-medium">{{ $order->estimated_finish_at->format('H:i') }}</div>
                                        <div class="text-xs {{ $order->isOverdue() ? 'text-red-600 font-bold' : 'text-gray-500' }}">
                                            @if($order->status == 'selesai' || $order->status == 'diambil' || $order->status == 'batal')
                                            Selesai
                                            @elseif($order->isOverdue())
                                            Terlambat {{ $order->getRemainingTime() }}
                                            @else
                                            {{ $order->getRemainingTime() }} lagi
                                            @endif
                                        </div>
                                        @else
                                        <div class="text-gray-400">-</div>
                                        @endif
                                    </td>
                                    <td class="py-4">
                                        @if($order->payment_status == 'paid')
                                        <span class="bg-green-100 text-green-800 px-2 py-1 rounded text-xs font-medium">
                                            <i class="fas fa-check mr-1"></i>Lunas
                                        </span>
                                        @else
                                        <span class="bg-red-100 text-red-800 px-2 py-1 rounded text-xs font-medium">
                                            <i class="fas fa-clock mr-1"></i>Belum
                                        </span>
                                        @endif
                                    </td>
                                    <td class="py-4">
                                        <div class="flex space-x-2">
                                            <!-- Status Update Buttons -->
                                            @if($order->status == 'pending')
                                            <form method="POST" action="{{ route('orders.status', $order) }}" class="inline">
                                                @csrf
                                                <button type="submit" name="status" value="cuci" class="bg-blue-100 text-blue-800 px-3 py-1 rounded text-sm hover:bg-blue-200">
                                                    <i class="fas fa-soap mr-1"></i>Cuci
                                                </button>
                                            </form>
                                            @elseif($order->status == 'cuci')
                                            <form method="POST" action="{{ route('orders.status', $order) }}" class="inline">
                                                @csrf
                                                <button type="submit" name="status" value="kering" class="bg-yellow-100 text-yellow-800 px-3 py-1 rounded text-sm hover:bg-yellow-200">
                                                    <i class="fas fa-wind mr-1"></i>Kering
                                                </button>
                                            </form>
                                            @elseif($order->status == 'kering')
                                            <form method="POST" action="{{ route('orders.status', $order) }}" class="inline">
                                                @csrf
                                                <button type="submit" name="status" value="setrika" class="bg-orange-100 text-orange-800 px-3 py-1 rounded text-sm hover:bg-orange-200">
                                                    <i class="fas fa-iron mr-1"></i>Setrika
                                                </button>
                                            </form>
                                            @elseif($order->status == 'setrika')
                                            <form method="POST" action="{{ route('orders.status', $order) }}" class="inline">
                                                @csrf
                                                <button type="submit" name="status" value="selesai" class="bg-green-100 text-green-800 px-3 py-1 rounded text-sm hover:bg-green-200">
                                                    <i class="fas fa-check mr-1"></i>Selesai
                                                </button>
                                            </form>
                                            @elseif($order->status == 'selesai')
                                            <form method="POST" action="{{ route('orders.status', $order) }}" class="inline">
                                                @csrf
                                                <button type="submit" name="status" value="diambil" class="bg-purple-100 text-purple-800 px-3 py-1 rounded text-sm hover:bg-purple-200">
                                                    <i class="fas fa-truck mr-1"></i>Diambil
                                                </button>
                                            </form>
                                            @endif
                                            
                                            <!-- Payment Button -->
                                            @if($order->payment_status == 'unpaid')
                                            <form method="POST" action="{{ route('orders.paid', $order) }}" class="inline">
                                                @csrf
                                                <button type="submit" class="bg-green-100 text-green-800 px-3 py-1 rounded text-sm hover:bg-green-200">
                                                    <i class="fas fa-money-bill mr-1"></i>Bayar
                                                </button>
                                            </form>
                                            @endif
                                            
                                            <!-- View Details -->
                                            <a href="{{ route('orders.show', $order) }}" class="bg-gray-100 text-gray-800 px-3 py-1 rounded text-sm hover:bg-gray-200 inline-block">
                                                <i class="fas fa-eye mr-1"></i>Detail
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        
                        @if($recentOrders->isEmpty())
                        <div class="text-center py-8 text-gray-500">
                            <i class="fas fa-inbox text-4xl mb-2"></i>
                            <p>Tidak ada order hari ini</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Tab 3: Riwayat Pelanggan -->
            <div id="riwayat-tab" class="tab-pane hidden">
                <div class="bg-white rounded-xl shadow-md p-6">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-2xl font-bold text-gray-800">
                            <i class="fas fa-history text-blue-600 mr-2"></i>Riwayat Pelanggan
                        </h2>
                        <div class="w-1/3">
                            <div class="relative">
                                <input type="text" id="searchCustomer" 
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                       placeholder="Cari pelanggan..."
                                       onkeyup="searchCustomers()">
                                <i class="fas fa-search absolute right-3 top-3 text-gray-400"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Stats Pelanggan -->
                    @php
                        $totalCustomers = App\Models\Customer::count();
                        $activeCustomers = App\Models\Customer::where('total_orders', '>', 0)->count();
                        $totalTransactions = App\Models\Order::count();
                        $totalRevenue = App\Models\Order::sum('total_price');
                    @endphp
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                        <div class="bg-blue-50 p-4 rounded-lg">
                            <p class="text-gray-600 text-sm">Total Pelanggan</p>
                            <p class="text-2xl font-bold text-blue-600">{{ $totalCustomers }}</p>
                        </div>
                        <div class="bg-green-50 p-4 rounded-lg">
                            <p class="text-gray-600 text-sm">Pelanggan Aktif</p>
                            <p class="text-2xl font-bold text-green-600">{{ $activeCustomers }}</p>
                        </div>
                        <div class="bg-purple-50 p-4 rounded-lg">
                            <p class="text-gray-600 text-sm">Total Transaksi</p>
                            <p class="text-2xl font-bold text-purple-600">{{ $totalTransactions }}</p>
                        </div>
                        <div class="bg-yellow-50 p-4 rounded-lg">
                            <p class="text-gray-600 text-sm">Total Pendapatan</p>
                            <p class="text-2xl font-bold text-yellow-600">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</p>
                        </div>
                    </div>

                    <!-- Daftar Pelanggan -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6" id="customersList">
                        @foreach(App\Models\Customer::orderBy('total_orders', 'desc')->limit(8)->get() as $customer)
                        <div class="border border-gray-200 rounded-lg p-4 hover:border-blue-300 hover:shadow-md transition">
                            <div class="flex justify-between items-start mb-4">
                                <div class="flex items-center">
                                    <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center mr-3">
                                        <i class="fas fa-user text-blue-600 text-xl"></i>
                                    </div>
                                    <div>
                                        <h3 class="font-bold text-gray-800">{{ $customer->name }}</h3>
                                        <p class="text-sm text-gray-500">{{ $customer->phone }}</p>
                                    </div>
                                </div>
                                <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-xs font-medium">
                                    {{ $customer->total_orders }} order
                                </span>
                            </div>
                            
                            <div class="space-y-2 mb-4">
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Total Order</span>
                                    <span class="font-medium">{{ $customer->total_orders }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Total Belanja</span>
                                    <span class="font-bold text-blue-600">Rp {{ number_format($customer->total_spent, 0, ',', '.') }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Order Terakhir</span>
                                    <span class="font-medium">
                                        @if($customer->last_order_date)
                                            {{ \Carbon\Carbon::parse($customer->last_order_date)->format('d/m/Y') }}
                                        @else
                                            -
                                        @endif
                                    </span>
                                </div>
                            </div>
                            
                            <button onclick="viewCustomerHistory({{ $customer->id }})" class="w-full bg-blue-50 text-blue-700 py-2 rounded-lg hover:bg-blue-100 transition">
                                <i class="fas fa-eye mr-2"></i>Lihat Riwayat Order
                            </button>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Tambah Pelanggan -->
    <div id="customerModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50 p-4">
        <div class="bg-white rounded-xl shadow-2xl max-w-md w-full">
            <div class="p-6">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-xl font-bold text-gray-800">
                        <i class="fas fa-user-plus text-blue-600 mr-2"></i>Tambah Pelanggan Baru
                    </h3>
                    <button onclick="closeCustomerModal()" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
                
                <form id="addCustomerForm" method="POST" action="{{ route('customers.store') }}">
                    @csrf
                    <div class="space-y-4">
                        <div>
                            <label class="block text-gray-700 font-medium mb-2">Nama Lengkap *</label>
                            <input type="text" name="name" required
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                   placeholder="Nama pelanggan">
                        </div>
                        
                        <div>
                            <label class="block text-gray-700 font-medium mb-2">No. Telepon *</label>
                            <input type="tel" name="phone" required
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                   placeholder="0812-3456-7890">
                        </div>
                        
                        <div>
                            <label class="block text-gray-700 font-medium mb-2">Email</label>
                            <input type="email" name="email"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                   placeholder="email@example.com">
                        </div>
                        
                        <div>
                            <label class="block text-gray-700 font-medium mb-2">Alamat</label>
                            <textarea name="address" rows="2"
                                      class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                      placeholder="Alamat lengkap"></textarea>
                        </div>
                        
                        <div>
                            <label class="block text-gray-700 font-medium mb-2">Catatan</label>
                            <textarea name="notes" rows="2"
                                      class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                      placeholder="Catatan khusus"></textarea>
                        </div>
                    </div>
                    
                    <div class="mt-6 flex space-x-4">
                        <button type="submit" class="flex-1 bg-blue-600 text-white py-3 rounded-lg font-bold hover:bg-blue-700 transition">
                            <i class="fas fa-save mr-2"></i>Simpan
                        </button>
                        <button type="button" onclick="closeCustomerModal()" class="flex-1 bg-gray-200 text-gray-800 py-3 rounded-lg font-medium hover:bg-gray-300 transition">
                            Batal
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- JavaScript -->
    <script>
        // Tab Management
        function showTab(tabName) {
            // Hide all tabs
            document.querySelectorAll('.tab-pane').forEach(tab => {
                tab.classList.add('hidden');
            });
            
            // Remove active from all tab buttons
            document.querySelectorAll('.tab-button').forEach(button => {
                button.classList.remove('tab-active', 'text-blue-600');
                button.classList.add('text-gray-500');
            });
            
            // Show selected tab
            document.getElementById(tabName + '-tab').classList.remove('hidden');
            
            // Add active to selected tab button
            document.getElementById('tab-' + tabName).classList.add('tab-active', 'text-blue-600');
            document.getElementById('tab-' + tabName).classList.remove('text-gray-500');
            
            // Save last active tab to localStorage
            localStorage.setItem('lastActiveTab', tabName);
        }

        // Load last active tab
        document.addEventListener('DOMContentLoaded', function() {
            const lastTab = localStorage.getItem('lastActiveTab') || 'penerimaan';
            showTab(lastTab);
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

        // Price calculation
        function calculatePrice() {
            const serviceSelect = document.getElementById('service_id');
            const selectedOption = serviceSelect.options[serviceSelect.selectedIndex];
            const weight = parseFloat(document.getElementById('weight').value) || 0;
            
            if (selectedOption.value && weight > 0) {
                const pricePerKg = parseFloat(selectedOption.getAttribute('data-price'));
                const estimatedHours = parseInt(selectedOption.getAttribute('data-hours'));
                const totalPrice = pricePerKg * weight;
                
                // Update price fields
                document.getElementById('price_per_kg').value = pricePerKg;
                document.getElementById('total_price').value = totalPrice.toLocaleString('id-ID');
                document.getElementById('total_price_hidden').value = totalPrice;
                document.getElementById('totalPriceDisplay').textContent = 'Rp ' + totalPrice.toLocaleString('id-ID');
                
                // Update estimated finish time
                const now = new Date();
                const finishTime = new Date(now.getTime() + estimatedHours * 60 * 60 * 1000);
                const finishTimeString = finishTime.toLocaleTimeString('id-ID', { 
                    hour: '2-digit', 
                    minute: '2-digit' 
                });
                document.getElementById('estimatedFinish').textContent = finishTimeString;
                
                // Update preview
                document.getElementById('previewService').textContent = selectedOption.text.split('(')[0].trim();
                document.getElementById('previewWeight').textContent = weight + ' kg';
                document.getElementById('previewTotal').textContent = 'Rp ' + totalPrice.toLocaleString('id-ID');
                document.getElementById('previewEstimate').textContent = finishTimeString + ' (' + estimatedHours + ' jam)';
                
                // Update customer preview
                const customerSelect = document.getElementById('customer_id');
                const customerText = customerSelect.options[customerSelect.selectedIndex].text;
                document.getElementById('previewCustomer').textContent = customerText || '-';
                
                // Update payment preview
                const paymentStatus = document.querySelector('input[name="payment_status"]:checked').value;
                document.getElementById('previewPayment').textContent = paymentStatus === 'paid' ? 'LUNAS' : 'BELUM BAYAR';
                document.getElementById('previewPayment').className = paymentStatus === 'paid' ? 
                    'font-medium text-green-600' : 'font-medium text-red-600';
            }
        }

        // Update preview when customer changes
        document.getElementById('customer_id').addEventListener('change', function() {
            const customerText = this.options[this.selectedIndex].text;
            document.getElementById('previewCustomer').textContent = customerText || '-';
        });

        // Toggle payment fields
        function togglePaymentFields() {
            const paymentStatus = document.querySelector('input[name="payment_status"]:checked').value;
            document.getElementById('previewPayment').textContent = paymentStatus === 'paid' ? 'LUNAS' : 'BELUM BAYAR';
            document.getElementById('previewPayment').className = paymentStatus === 'paid' ? 
                'font-medium text-green-600' : 'font-medium text-red-600';
        }

        // Clear form
        function clearForm() {
            if (confirm('Bersihkan semua data form?')) {
                document.getElementById('orderForm').reset();
                document.getElementById('total_price').value = '0';
                document.getElementById('total_price_hidden').value = '0';
                document.getElementById('totalPriceDisplay').textContent = 'Rp 0';
                document.getElementById('estimatedFinish').textContent = '-';
                document.getElementById('previewCustomer').textContent = '-';
                document.getElementById('previewService').textContent = '-';
                document.getElementById('previewWeight').textContent = '-';
                document.getElementById('previewTotal').textContent = 'Rp 0';
                document.getElementById('previewEstimate').textContent = '-';
                document.getElementById('previewPayment').textContent = 'LUNAS';
            }
        }

        // Customer modal
        function showAddCustomerModal() {
            document.getElementById('customerModal').classList.remove('hidden');
        }

        function closeCustomerModal() {
            document.getElementById('customerModal').classList.add('hidden');
        }

        // Handle customer form submission via AJAX
        document.getElementById('addCustomerForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const response = await fetch(this.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            });
            
            const data = await response.json();
            
            if (data.success) {
                // Add new customer to select
                const select = document.getElementById('customer_id');
                const option = document.createElement('option');
                option.value = data.customer.id;
                option.textContent = `${data.customer.name} (${data.customer.phone})`;
                select.appendChild(option);
                select.value = data.customer.id;
                
                // Update preview
                document.getElementById('previewCustomer').textContent = `${data.customer.name} (${data.customer.phone})`;
                
                // Close modal and reset form
                closeCustomerModal();
                this.reset();
                
                alert('Pelanggan berhasil ditambahkan!');
            } else {
                alert('Gagal menambahkan pelanggan. Silakan coba lagi.');
            }
        });

        // Filter orders by status
        function filterOrders() {
            const status = document.getElementById('filterStatus').value;
            const rows = document.querySelectorAll('#ordersTableBody tr');
            
            rows.forEach(row => {
                if (status === 'all' || row.getAttribute('data-status') === status) {
                    row.classList.remove('hidden');
                } else {
                    row.classList.add('hidden');
                }
            });
        }

        // Refresh orders
        function refreshOrders() {
            window.location.reload();
        }

        // Search customers
        async function searchCustomers() {
            const searchTerm = document.getElementById('searchCustomer').value;
            
            if (searchTerm.length < 2) {
                // Reset to initial list
                // In a real app, you would fetch the initial list again
                return;
            }
            
            // In a real app, you would make an AJAX request here
            // For now, we'll just filter the visible customers
            const customers = document.querySelectorAll('#customersList > div');
            customers.forEach(customerDiv => {
                const name = customerDiv.querySelector('h3').textContent.toLowerCase();
                const phone = customerDiv.querySelector('p.text-sm').textContent.toLowerCase();
                
                if (name.includes(searchTerm.toLowerCase()) || phone.includes(searchTerm.toLowerCase())) {
                    customerDiv.classList.remove('hidden');
                } else {
                    customerDiv.classList.add('hidden');
                }
            });
        }

        // View customer history
        function viewCustomerHistory(customerId) {
            window.location.href = `/customers/${customerId}`;
        }

        // Show notifications
        function showNotifications() {
            if (@json($overdueOrders->count()) > 0) {
                alert(`Ada ${@json($overdueOrders->count())} order yang terlambat! Periksa tab Status Cucian.`);
            } else {
                alert('Tidak ada notifikasi baru.');
            }
        }

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
                closeCustomerModal();
            }
        });

        // Initialize
        updateTime();
        togglePaymentFields();
    </script>

</body>
</html>