<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Customer;
use App\Models\Service;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        if (auth()->check() && auth()->user()->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }

        // Statistics for today
        $today = now()->format('Y-m-d');
        
        $todayOrders = Order::whereDate('created_at', $today)->count();
        $todayRevenue = Order::whereDate('created_at', $today)->sum('total_price');
        $todayPaid = Order::whereDate('created_at', $today)->where('payment_status', 'paid')->sum('paid_amount');
        
        // Orders by status
        $statusCounts = [
            'pending' => Order::where('status', 'pending')->count(),
            'cuci' => Order::where('status', 'cuci')->count(),
            'kering' => Order::where('status', 'kering')->count(),
            'setrika' => Order::where('status', 'setrika')->count(),
            'selesai' => Order::where('status', 'selesai')->count(),
            'diambil' => Order::where('status', 'diambil')->count(),
        ];
        
        // Recent orders
        $recentOrders = Order::with(['customer', 'service'])
            ->whereDate('created_at', $today)
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();
        
        // Overdue orders
        $overdueOrders = Order::where('estimated_finish_at', '<', now())
            ->whereIn('status', ['pending', 'cuci', 'kering', 'setrika'])
            ->with(['customer', 'service'])
            ->limit(5)
            ->get();
        
        return view('dashboard', compact(
            'todayOrders',
            'todayRevenue',
            'todayPaid',
            'statusCounts',
            'recentOrders',
            'overdueOrders'
        ));
    }
}
