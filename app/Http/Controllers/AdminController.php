<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Customer;
use App\Models\User;
use App\Models\Service;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AdminController extends Controller
{
    public function dashboard()
    {
        if (!auth()->check() || auth()->user()->role !== 'admin') {
            abort(403, 'Unauthorized access');
        }

        // Date ranges
        $today = Carbon::today();
        $startOfWeek = Carbon::now()->startOfWeek();
        $endOfWeek = Carbon::now()->endOfWeek();
        $startOfMonth = Carbon::now()->startOfMonth();
        $endOfMonth = Carbon::now()->endOfMonth();
        $last7Days = Carbon::today()->subDays(6);
        
        // Today's Stats
        $todayOrders = Order::whereDate('created_at', $today)->count();
        $todayRevenue = Order::whereDate('created_at', $today)->sum('total_price');
        $todayCompleted = Order::whereDate('created_at', $today)
            ->where('status', 'selesai')
            ->count();
        $todayPaid = Order::whereDate('created_at', $today)
            ->where('payment_status', 'paid')
            ->sum('total_price');
        
        // Week's Stats
        $weekOrders = Order::whereBetween('created_at', [$startOfWeek, $endOfWeek])->count();
        $weekRevenue = Order::whereBetween('created_at', [$startOfWeek, $endOfWeek])->sum('total_price');
        
        // Month's Stats
        $monthOrders = Order::whereBetween('created_at', [$startOfMonth, $endOfMonth])->count();
        $monthRevenue = Order::whereBetween('created_at', [$startOfMonth, $endOfMonth])->sum('total_price');
        
        // Order Status Distribution
        $statusCounts = [
            'pending' => Order::where('status', 'pending')->count(),
            'cuci' => Order::where('status', 'cuci')->count(),
            'kering' => Order::where('status', 'kering')->count(),
            'setrika' => Order::where('status', 'setrika')->count(),
            'selesai' => Order::where('status', 'selesai')->count(),
            'diambil' => Order::where('status', 'diambil')->count(),
            'batal' => Order::where('status', 'batal')->count(),
        ];
        
        // Payment Status
        $paymentStats = [
            'paid' => Order::where('payment_status', 'paid')->sum('total_price'),
            'unpaid' => Order::where('payment_status', 'unpaid')->sum('total_price'),
        ];
        
        // Customer Stats
        $totalCustomers = Customer::count();
        $newCustomersToday = Customer::whereDate('created_at', $today)->count();
        $activeCustomers = Customer::where('total_orders', '>', 0)->count();
        
        // Staff Stats
        $totalStaff = User::where('role', 'kasir')->count();
        $activeStaff = User::where('role', 'kasir')->count(); 
        
        // Service Popularity
        $popularServices = Service::withCount(['orders' => function($query) use ($last7Days) {
                $query->where('created_at', '>=', $last7Days);
            }])
            ->orderBy('orders_count', 'desc')
            ->take(5)
            ->get();
        
        // Daily Revenue for Last 7 Days
        $dailyRevenue = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $dayName = $date->format('D');
            $revenue = Order::whereDate('created_at', $date)->sum('total_price');
            $orders = Order::whereDate('created_at', $date)->count();
            
            $dailyRevenue[] = [
                'day' => $dayName,
                'date' => $date->format('d/m'),
                'revenue' => $revenue,
                'orders' => $orders
            ];
        }
        
        // Recent Orders
        $recentOrders = Order::with(['customer', 'service', 'user'])
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();
        
        // Top Customers
        $topCustomers = Customer::orderBy('total_spent', 'desc')
            ->orderBy('total_orders', 'desc')
            ->take(5)
            ->get();
        
        // Overdue Orders
        $overdueOrders = Order::where('estimated_finish_at', '<', now())
            ->whereIn('status', ['pending', 'cuci', 'kering', 'setrika'])
            ->with(['customer', 'service'])
            ->take(5)
            ->get();
        
        return view('admin.dashboard', compact(
            'todayOrders',
            'todayRevenue',
            'todayCompleted',
            'todayPaid',
            'weekOrders',
            'weekRevenue',
            'monthOrders',
            'monthRevenue',
            'statusCounts',
            'paymentStats',
            'totalCustomers',
            'newCustomersToday',
            'activeCustomers',
            'totalStaff',
            'activeStaff',
            'popularServices',
            'dailyRevenue',
            'recentOrders',
            'topCustomers',
            'overdueOrders'
        ));
    }
    
    public function reports()
    {
        if (!auth()->check() || auth()->user()->role !== 'admin') {
            abort(403, 'Unauthorized access');
        }

        // Filter options
        $dateRange = request()->get('range', 'today');
        $status = request()->get('status', 'all');
        
        $query = Order::with(['customer', 'service', 'user']);
        
        // Apply date filter
        if ($dateRange === 'today') {
            $query->whereDate('created_at', Carbon::today());
        } elseif ($dateRange === 'week') {
            $query->whereBetween('created_at', [
                Carbon::now()->startOfWeek(),
                Carbon::now()->endOfWeek()
            ]);
        } elseif ($dateRange === 'month') {
            $query->whereBetween('created_at', [
                Carbon::now()->startOfMonth(),
                Carbon::now()->endOfMonth()
            ]);
        }
        
        // Apply status filter
        if ($status !== 'all') {
            $query->where('status', $status);
        }
        
        $orders = $query->orderBy('created_at', 'desc')->paginate(50);
        
        return view('admin.reports', compact('orders', 'dateRange', 'status'));
    }
    
    public function staffManagement()
    {
        if (!auth()->check() || auth()->user()->role !== 'admin') {
            abort(403, 'Unauthorized access');
        }

        $staff = User::where('role', 'kasir')->get();
        return view('admin.staff', compact('staff'));
    }
    
    public function serviceManagement()
    {
        if (!auth()->check() || auth()->user()->role !== 'admin') {
            abort(403, 'Unauthorized access');
        }

        $services = Service::all();
        return view('admin.services', compact('services'));
    }

    public function customers()
    {
        if (!auth()->check() || auth()->user()->role !== 'admin') {
            abort(403, 'Unauthorized access');
        }

        $search = request()->get('search');
        $query = Customer::query();

        if ($search) {
            $query->where('name', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('address', 'like', "%{$search}%");
        }

        $customers = $query->orderBy('name', 'asc')->paginate(20);
        return view('admin.customers', compact('customers', 'search'));
    }
}
