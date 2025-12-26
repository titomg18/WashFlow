<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Customer;
use App\Models\Service;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::with(['customer', 'service', 'user'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);
        
        return view('orders.index', compact('orders'));
    }

    public function create()
    {
        $customers = Customer::orderBy('name')->get();
        $services = Service::where('is_active', true)->orderBy('order')->get();
        
        return view('orders.create', compact('customers', 'services'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'service_id' => 'required|exists:services,id',
            'weight' => 'required|numeric|min:0.1|max:100',
            'special_notes' => 'nullable|string|max:500',
            'payment_status' => 'required|in:unpaid,paid',
        ]);

        $service = Service::findOrFail($validated['service_id']);
        
        // Calculate prices
        $pricePerKg = $service->price_per_kg;
        $totalPrice = $validated['weight'] * $pricePerKg;
        
        // Create order
        $order = Order::create([
            'customer_id' => $validated['customer_id'],
            'user_id' => auth()->id(),
            'service_id' => $validated['service_id'],
            'weight' => $validated['weight'],
            'price_per_kg' => $pricePerKg,
            'total_price' => $totalPrice,
            'special_notes' => $validated['special_notes'],
            'estimated_finish_at' => now()->addHours($service->estimated_hours),
            'payment_status' => $validated['payment_status'],
            'paid_amount' => $validated['payment_status'] === 'paid' ? $totalPrice : 0,
            'payment_method' => 'cash',
        ]);

        return redirect()->route('orders.show', $order)
            ->with('success', 'Order berhasil dibuat!');
    }

    public function show(Order $order)
    {
        $order->load(['customer', 'service', 'user', 'statusLogs.user']);
        return view('orders.show', compact('order'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:pending,cuci,kering,setrika,selesai,diambil,batal',
            'notes' => 'nullable|string|max:500',
        ]);

        $order->updateStatus($request->status, $request->notes);

        return back()->with('success', 'Status berhasil diperbarui!');
    }

    public function markAsPaid(Order $order)
    {
        $order->markAsPaid();
        return back()->with('success', 'Status pembayaran berhasil diperbarui!');
    }
}