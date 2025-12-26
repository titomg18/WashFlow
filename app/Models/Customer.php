<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'phone',
        'email',
        'address',
        'total_orders',
        'total_spent',
        'last_order_date',
        'notes',
    ];

    protected static function boot()
    {
        parent::boot();

        // Auto generate customer code
        static::creating(function ($customer) {
            if (empty($customer->code)) {
                $latest = Customer::latest()->first();
                $nextId = $latest ? $latest->id + 1 : 1;
                $customer->code = 'CUST-' . str_pad($nextId, 4, '0', STR_PAD_LEFT);
            }
        });
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function getFormattedPhoneAttribute()
    {
        $phone = $this->phone;
        if (strlen($phone) == 12) {
            return substr($phone, 0, 4) . '-' . substr($phone, 4, 4) . '-' . substr($phone, 8);
        }
        return $phone;
    }
}