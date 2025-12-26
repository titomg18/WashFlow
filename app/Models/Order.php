<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_number',
        'customer_id',
        'user_id',
        'service_id',
        'weight',
        'price_per_kg',
        'total_price',
        'status',
        'estimated_finish_at',
        'actual_finish_at',
        'picked_up_at',
        'payment_status',
        'paid_amount',
        'payment_method',
        'special_notes',
        'internal_notes',
    ];

    protected $casts = [
        'weight' => 'decimal:2',
        'price_per_kg' => 'decimal:2',
        'total_price' => 'decimal:2',
        'paid_amount' => 'decimal:2',
        'estimated_finish_at' => 'datetime',
        'actual_finish_at' => 'datetime',
        'picked_up_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();

        // Auto generate invoice number
        static::creating(function ($order) {
            if (empty($order->invoice_number)) {
                $date = now()->format('Ymd');
                $latest = Order::whereDate('created_at', today())->latest()->first();
                $number = $latest ? (int) substr($latest->invoice_number, -3) + 1 : 1;
                $order->invoice_number = 'WF-' . $date . '-' . str_pad($number, 3, '0', STR_PAD_LEFT);
            }
        });

        // Update customer stats when order is created
        static::created(function ($order) {
            $customer = $order->customer;
            $customer->increment('total_orders');
            $customer->total_spent += $order->total_price;
            $customer->last_order_date = now();
            $customer->save();
        });
    }

    // Relationships
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    public function statusLogs()
    {
        return $this->hasMany(OrderStatusLog::class);
    }

    // Helper methods
    public function markAsPaid()
    {
        $this->update([
            'payment_status' => 'paid',
            'paid_amount' => $this->total_price,
        ]);
    }

    public function updateStatus($status, $notes = null)
    {
        $this->update(['status' => $status]);
        
        // Log status change
        OrderStatusLog::create([
            'order_id' => $this->id,
            'user_id' => auth()->id(),
            'status' => $status,
            'notes' => $notes,
        ]);

        // If status is 'selesai', set actual_finish_at
        if ($status === 'selesai' && !$this->actual_finish_at) {
            $this->update(['actual_finish_at' => now()]);
        }

        // If status is 'diambil', set picked_up_at
        if ($status === 'diambil' && !$this->picked_up_at) {
            $this->update(['picked_up_at' => now()]);
        }
    }

    public function isOverdue()
    {
        if (!$this->estimated_finish_at) return false;
        return $this->status !== 'selesai' && $this->status !== 'diambil' && now() > $this->estimated_finish_at;
    }

    public function getRemainingTime()
    {
        if (!$this->estimated_finish_at) return null;
        
        $diff = now()->diff($this->estimated_finish_at);
        return $diff->format('%h jam %i menit');
    }
}