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
        .status-cuci {
            background-color: #dbeafe;
            color: #1d4ed8;
        }
        .status-kering {
            background-color: #fef3c7;
            color: #d97706;
        }
        .status-selesai {
            background-color: #d1fae5;
            color: #065f46;
        }
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
                    <button class="relative p-2 hover:bg-blue-700 rounded-lg transition">
                        <i class="fas fa-bell text-xl"></i>
                        <span class="absolute top-1 right-1 w-2 h-2 bg-red-500 rounded-full"></span>
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
                                <p class="text-xs text-gray-500">Kasir</p>
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
                        <i class="fas fa-coins mr-2"></i>Hari ini: Rp 1,250,000
                    </div>
                    <div class="bg-blue-100 text-blue-800 px-4 py-2 rounded-lg font-bold">
                        <i class="fas fa-receipt mr-2"></i>12 Transaksi
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
                                    <label class="block text-gray-700 font-medium mb-2">Nama Pelanggan</label>
                                    <div class="relative">
                                        <input type="text" id="customerName" 
                                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                               placeholder="Nama lengkap pelanggan">
                                    </div>
                                </div>
                                
                                <div>
                                    <label class="block text-gray-700 font-medium mb-2">No. Telepon</label>
                                    <input type="tel" id="customerPhone" 
                                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                           placeholder="0812-3456-7890">
                                </div>
                                
                                <div>
                                    <label class="block text-gray-700 font-medium mb-2">Alamat</label>
                                    <input type="text" id="customerAddress" 
                                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                           placeholder="Alamat lengkap">
                                </div>
                                
                                <div>
                                    <label class="block text-gray-700 font-medium mb-2">Email</label>
                                    <input type="email" id="customerEmail" 
                                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                           placeholder="email@example.com">
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
                                    <label class="block text-gray-700 font-medium mb-2">Jenis Layanan</label>
                                    <select id="serviceType" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                                        <option value="">Pilih Layanan</option>
                                        <option value="cuci">Cuci Reguler (Rp 10,000/kg)</option>
                                        <option value="kering">Cuci + Kering (Rp 15,000/kg)</option>
                                        <option value="setrika">Cuci + Setrika (Rp 20,000/kg)</option>
                                        <option value="express">Express 6 Jam (Rp 25,000/kg)</option>
                                        <option value="dryclean">Dry Cleaning (Rp 30,000/kg)</option>
                                    </select>
                                </div>
                                
                                <div>
                                    <label class="block text-gray-700 font-medium mb-2">Berat (kg)</label>
                                    <input type="number" id="weight" min="0.5" step="0.5" 
                                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                           placeholder="0.5"
                                           oninput="calculatePrice()">
                                </div>
                                
                                <div>
                                    <label class="block text-gray-700 font-medium mb-2">Harga</label>
                                    <div class="relative">
                                        <div class="absolute left-0 pl-3 flex items-center h-full">
                                            <span class="text-gray-500">Rp</span>
                                        </div>
                                        <input type="text" id="price" readonly
                                               class="w-full pl-10 pr-4 py-3 border border-gray-300 bg-gray-50 rounded-lg"
                                               value="0">
                                    </div>
                                </div>
                            </div>

                            <!-- Catatan Khusus -->
                            <div class="mb-6">
                                <label class="block text-gray-700 font-medium mb-2">Catatan Khusus</label>
                                <textarea id="specialNote" 
                                          class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                          rows="2"
                                          placeholder="Contoh: Sabun khusus, noda anggur, dll."></textarea>
                            </div>

                            <!-- Estimasi Waktu -->
                            <div class="bg-blue-50 p-4 rounded-lg">
                                <div class="flex justify-between items-center">
                                    <div>
                                        <p class="text-gray-600 text-sm">Estimasi Selesai</p>
                                        <p class="text-xl font-bold text-blue-600" id="estimatedFinish">-</p>
                                    </div>
                                    <div>
                                        <p class="text-gray-600 text-sm">Total</p>
                                        <p class="text-2xl font-bold text-blue-600" id="totalPrice">Rp 0</p>
                                    </div>
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
                                <button onclick="processLaundry()" 
                                        class="w-full bg-gradient-to-r from-green-600 to-emerald-600 text-white py-3 rounded-lg font-bold hover:from-green-700 hover:to-emerald-700 transition">
                                    <i class="fas fa-check-circle mr-2"></i>Simpan & Cetak Nota
                                </button>
                                
                                <button onclick="saveDraft()" 
                                        class="w-full bg-yellow-100 text-yellow-800 py-3 rounded-lg font-medium hover:bg-yellow-200 transition">
                                    <i class="fas fa-save mr-2"></i>Simpan Draft
                                </button>
                                
                                <button onclick="clearForm()" 
                                        class="w-full bg-gray-200 text-gray-800 py-3 rounded-lg font-medium hover:bg-gray-300 transition">
                                    <i class="fas fa-times mr-2"></i>Bersihkan Form
                                </button>
                            </div>
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
                                </div>
                                
                                <!-- Footer -->
                                <div class="text-center mt-4 pt-4 border-t">
                                    <p class="text-xs text-gray-500">Simpan nota ini sebagai bukti penerimaan</p>
                                </div>
                            </div>
                        </div>

                        <!-- Statistik Cepat -->
                        <div class="bg-white rounded-xl shadow-md p-6">
                            <h2 class="text-xl font-bold text-gray-800 mb-4">
                                <i class="fas fa-chart-line text-blue-600 mr-2"></i>Statistik Hari Ini
                            </h2>
                            <div class="space-y-3">
                                <div class="flex justify-between items-center">
                                    <span class="text-gray-600">Cucian Masuk</span>
                                    <span class="font-bold">12</span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-gray-600">Dalam Proses</span>
                                    <span class="font-bold">8</span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-gray-600">Selesai</span>
                                    <span class="font-bold">4</span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-gray-600">Rata-rata Waktu</span>
                                    <span class="font-bold">3.5 jam</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tab 2: Status Cucian -->
            <div id="status-tab" class="tab-pane hidden">
                <div class="bg-white rounded-xl shadow-md p-6">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-2xl font-bold text-gray-800">
                            <i class="fas fa-tasks text-blue-600 mr-2"></i>Manajemen Status Cucian
                        </h2>
                        <div class="flex space-x-4">
                            <select id="filterStatus" class="px-4 py-2 border border-gray-300 rounded-lg">
                                <option value="all">Semua Status</option>
                                <option value="cuci">Cuci</option>
                                <option value="kering">Kering</option>
                                <option value="selesai">Selesai</option>
                            </select>
                            <button onclick="refreshStatus()" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                                <i class="fas fa-sync-alt mr-2"></i>Refresh
                            </button>
                        </div>
                    </div>

                    <!-- Stats Cards -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                        <div class="bg-blue-50 border-l-4 border-blue-500 p-6 rounded-lg">
                            <div class="flex justify-between items-center">
                                <div>
                                    <p class="text-gray-600">Cuci</p>
                                    <p class="text-3xl font-bold text-gray-800">5</p>
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
                                    <p class="text-3xl font-bold text-gray-800">3</p>
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
                                    <p class="text-3xl font-bold text-gray-800">4</p>
                                </div>
                                <div class="bg-green-100 p-3 rounded-full">
                                    <i class="fas fa-check-circle text-green-600 text-xl"></i>
                                </div>
                            </div>
                        </div>
                    </div>

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
                                    <th class="text-left py-3 text-gray-600 font-medium">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @for($i = 1; $i <= 5; $i++)
                                @php
                                    $statuses = [
                                        ['status' => 'cuci', 'text' => 'Cuci', 'icon' => '🧼', 'time' => '+1 jam'],
                                        ['status' => 'kering', 'text' => 'Kering', 'icon' => '🌬️', 'time' => '+30 menit'],
                                        ['status' => 'selesai', 'text' => 'Selesai', 'icon' => '✅', 'time' => 'Selesai']
                                    ];
                                    $status = $statuses[array_rand($statuses)];
                                    $customers = ['Budi Santoso', 'Siti Rahayu', 'Ahmad Wijaya', 'Dewi Lestari', 'Rudi Hartono'];
                                @endphp
                                <tr class="border-b hover:bg-gray-50">
                                    <td class="py-4">
                                        <div class="font-bold text-blue-600">WF-{{ date('Ymd') }}-00{{ $i }}</div>
                                        <div class="text-xs text-gray-500">{{ date('H:i', strtotime("-{$i} hour")) }}</div>
                                    </td>
                                    <td class="py-4">
                                        <div class="font-medium">{{ $customers[$i-1] }}</div>
                                        <div class="text-xs text-gray-500">0812-3456-789{{ $i }}</div>
                                    </td>
                                    <td class="py-4">
                                        <div class="font-medium">Cuci + Setrika</div>
                                        <div class="text-xs text-gray-500">2.5 kg • Rp 50,000</div>
                                    </td>
                                    <td class="py-4">
                                        <div class="flex items-center">
                                            <span class="text-xl mr-2">{{ $status['icon'] }}</span>
                                            <span class="status-badge status-{{ $status['status'] }}">
                                                {{ $status['text'] }}
                                            </span>
                                        </div>
                                    </td>
                                    <td class="py-4">
                                        <div class="font-medium">{{ date('H:i', strtotime($status['time'])) }}</div>
                                        <div class="text-xs text-gray-500">
                                            @if($status['status'] != 'selesai')
                                            {{ $status['time'] }} lagi
                                            @endif
                                        </div>
                                    </td>
                                    <td class="py-4">
                                        <div class="flex space-x-2">
                                            @if($status['status'] == 'cuci')
                                            <button onclick="updateStatus({{ $i }}, 'kering')" class="bg-yellow-100 text-yellow-800 px-3 py-1 rounded text-sm hover:bg-yellow-200">
                                                <i class="fas fa-wind mr-1"></i>Kering
                                            </button>
                                            @elseif($status['status'] == 'kering')
                                            <button onclick="updateStatus({{ $i }}, 'selesai')" class="bg-green-100 text-green-800 px-3 py-1 rounded text-sm hover:bg-green-200">
                                                <i class="fas fa-check mr-1"></i>Selesai
                                            </button>
                                            @else
                                            <button onclick="notifyCustomer({{ $i }})" class="bg-blue-100 text-blue-800 px-3 py-1 rounded text-sm hover:bg-blue-200">
                                                <i class="fas fa-bell mr-1"></i>Notifikasi
                                            </button>
                                            @endif
                                            <button onclick="viewDetails({{ $i }})" class="bg-gray-100 text-gray-800 px-3 py-1 rounded text-sm hover:bg-gray-200">
                                                <i class="fas fa-eye mr-1"></i>Detail
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                @endfor
                            </tbody>
                        </table>
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
                                       placeholder="Cari pelanggan...">
                                <i class="fas fa-search absolute right-3 top-3 text-gray-400"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Stats Pelanggan -->
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                        <div class="bg-blue-50 p-4 rounded-lg">
                            <p class="text-gray-600 text-sm">Total Pelanggan</p>
                            <p class="text-2xl font-bold text-blue-600">245</p>
                        </div>
                        <div class="bg-green-50 p-4 rounded-lg">
                            <p class="text-gray-600 text-sm">Pelanggan Aktif</p>
                            <p class="text-2xl font-bold text-green-600">128</p>
                        </div>
                        <div class="bg-purple-50 p-4 rounded-lg">
                            <p class="text-gray-600 text-sm">Rata-rata Kunjungan</p>
                            <p class="text-2xl font-bold text-purple-600">4.2x</p>
                        </div>
                        <div class="bg-yellow-50 p-4 rounded-lg">
                            <p class="text-gray-600 text-sm">Total Transaksi</p>
                            <p class="text-2xl font-bold text-yellow-600">1,024</p>
                        </div>
                    </div>

                    <!-- Daftar Pelanggan -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        @for($i = 1; $i <= 4; $i++)
                        <div class="border border-gray-200 rounded-lg p-4 hover:border-blue-300 hover:shadow-md transition">
                            <div class="flex justify-between items-start mb-4">
                                <div class="flex items-center">
                                    <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center mr-3">
                                        <i class="fas fa-user text-blue-600 text-xl"></i>
                                    </div>
                                    <div>
                                        <h3 class="font-bold text-gray-800">Pelanggan {{ $i }}</h3>
                                        <p class="text-sm text-gray-500">0812-3456-789{{ $i }}</p>
                                    </div>
                                </div>
                                <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-xs font-medium">
                                    Aktif
                                </span>
                            </div>
                            
                            <div class="space-y-2 mb-4">
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Total Kunjungan</span>
                                    <span class="font-medium">{{ $i * 3 }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Transaksi Terakhir</span>
                                    <span class="font-medium">{{ date('d/m/Y', strtotime("-{$i} days")) }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Total Pengeluaran</span>
                                    <span class="font-bold text-blue-600">Rp {{ number_format($i * 150000, 0, ',', '.') }}</span>
                                </div>
                            </div>
                            
                            <button onclick="viewCustomerHistory({{ $i }})" class="w-full bg-gray-100 text-gray-800 py-2 rounded-lg hover:bg-gray-200 transition">
                                <i class="fas fa-eye mr-2"></i>Lihat Riwayat
                            </button>
                        </div>
                        @endfor
                    </div>
                </div>
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
        }

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
        const pricePerKg = {
            'cuci': 10000,
            'kering': 15000,
            'setrika': 20000,
            'express': 25000,
            'dryclean': 30000
        };

        const estimatedTime = {
            'cuci': '3 jam',
            'kering': '4 jam',
            'setrika': '5 jam',
            'express': '6 jam',
            'dryclean': '24 jam'
        };

        function calculatePrice() {
            const serviceType = document.getElementById('serviceType').value;
            const weight = parseFloat(document.getElementById('weight').value) || 0;
            
            if (serviceType && weight > 0) {
                const price = pricePerKg[serviceType] * weight;
                document.getElementById('price').value = price.toLocaleString('id-ID');
                document.getElementById('totalPrice').textContent = 'Rp ' + price.toLocaleString('id-ID');
                document.getElementById('estimatedFinish').textContent = estimatedTime[serviceType];
                
                // Update preview
                document.getElementById('previewService').textContent = document.getElementById('serviceType').options[document.getElementById('serviceType').selectedIndex].text;
                document.getElementById('previewWeight').textContent = weight + ' kg';
                document.getElementById('previewTotal').textContent = 'Rp ' + price.toLocaleString('id-ID');
                document.getElementById('previewEstimate').textContent = estimatedTime[serviceType];
                
                // Update customer preview
                const customerName = document.getElementById('customerName').value;
                document.getElementById('previewCustomer').textContent = customerName || '-';
            }
        }

        // Update customer preview
        document.getElementById('customerName').addEventListener('input', function() {
            document.getElementById('previewCustomer').textContent = this.value || '-';
        });

        // Form processing
        function processLaundry() {
            const customerName = document.getElementById('customerName').value;
            const serviceType = document.getElementById('serviceType').value;
            const weight = document.getElementById('weight').value;
            
            if (!customerName) {
                alert('Nama pelanggan harus diisi!');
                return;
            }
            
            if (!serviceType) {
                alert('Jenis layanan harus dipilih!');
                return;
            }
            
            if (!weight || weight <= 0) {
                alert('Berat cucian harus diisi!');
                return;
            }
            
            // Simulate processing
            alert('Cucian berhasil diterima! Nota akan dicetak.');
            
            // Reset form
            clearForm();
            
            // Switch to status tab
            showTab('status');
        }

        function saveDraft() {
            alert('Draft berhasil disimpan!');
        }

        function clearForm() {
            document.getElementById('customerName').value = '';
            document.getElementById('customerPhone').value = '';
            document.getElementById('customerAddress').value = '';
            document.getElementById('customerEmail').value = '';
            document.getElementById('serviceType').value = '';
            document.getElementById('weight').value = '';
            document.getElementById('price').value = '0';
            document.getElementById('specialNote').value = '';
            document.getElementById('totalPrice').textContent = 'Rp 0';
            document.getElementById('estimatedFinish').textContent = '-';
            
            // Reset preview
            document.getElementById('previewCustomer').textContent = '-';
            document.getElementById('previewService').textContent = '-';
            document.getElementById('previewWeight').textContent = '-';
            document.getElementById('previewTotal').textContent = 'Rp 0';
            document.getElementById('previewEstimate').textContent = '-';
        }

        // Status management
        function updateStatus(orderId, newStatus) {
            const statusText = {
                'cuci': 'Cuci',
                'kering': 'Kering',
                'selesai': 'Selesai'
            };
            
            if (confirm(`Ubah status order menjadi ${statusText[newStatus]}?`)) {
                alert(`Status order berhasil diubah menjadi ${statusText[newStatus]}`);
                refreshStatus();
            }
        }

        function notifyCustomer(orderId) {
            if (confirm('Kirim notifikasi ke pelanggan?')) {
                alert('Notifikasi berhasil dikirim ke pelanggan!');
            }
        }

        function viewDetails(orderId) {
            alert(`Menampilkan detail order #${orderId}`);
        }

        function refreshStatus() {
            const filter = document.getElementById('filterStatus').value;
            alert(`Memfilter status: ${filter}`);
            // In real app, this would refresh the table data
        }

        // Customer history
        function viewCustomerHistory(customerId) {
            alert(`Menampilkan riwayat pelanggan #${customerId}`);
        }

        // Search customer
        document.getElementById('searchCustomer').addEventListener('input', function(e) {
            const searchTerm = e.target.value.toLowerCase();
            // In real app, this would filter customer list
        });

        // User menu toggle (click instead of hover)
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