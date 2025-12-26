<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderStatusLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'user_id',
        'status',
        'notes',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getStatusBadgeAttribute()
    {
        $badges = [
            'pending' => 'bg-gray-100 text-gray-800',
            'cuci' => 'bg-blue-100 text-blue-800',
            'kering' => 'bg-yellow-100 text-yellow-800',
            'setrika' => 'bg-orange-100 text-orange-800',
            'selesai' => 'bg-green-100 text-green-800',
            'diambil' => 'bg-purple-100 text-purple-800',
            'batal' => 'bg-red-100 text-red-800',
        ];

        return $badges[$this->status] ?? 'bg-gray-100 text-gray-800';
    }
}