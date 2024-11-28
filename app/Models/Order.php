<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'address',
        'total_amount',
        'client_id',
        'order_status_id',
        'created_at',
    ];

    public function client()
    {
        return $this->belongsTo(User::class, 'client_id');
    }

    public function currentStatus()
    {
        return $this->belongsTo(OrderStatus::class, 'order_status_id');
    }

    // История статусов
    public function orderStatusHistory()
    {
        return $this->hasMany(OrderStatusesHistory::class, 'order_id');
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class, 'order_id');
    }

    public function calculateTotalAmount(): void
    {
        if (!$this->relationLoaded('orderItems')) {
            $this->load('orderItems');
        }
        $this->total_amount = $this->orderItems->sum('total_amount');
    }
    protected static function booted()
    {
        static::saving(function ($order) {
            $order->calculateTotalAmount();
        });

        static::updated(function ($order) {
            if ($order->isDirty('orderItems')) {
                $order->calculateTotalAmount();
                $order->saveQuietly();
            }
        });
    }
}
