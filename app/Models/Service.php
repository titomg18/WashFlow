<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'price_per_kg',
        'estimated_hours',
        'description',
        'is_active',
        'order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'price_per_kg' => 'decimal:2',
    ];

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function calculatePrice($weight)
    {
        return $weight * $this->price_per_kg;
    }

    public function getEstimatedFinishTime()
    {
        return now()->addHours($this->estimated_hours);
    }
}