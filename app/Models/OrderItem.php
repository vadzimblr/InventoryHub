<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    protected $fillable = [
        'quantity',
        'unit',
        'is_whole_unit',
        'total_amount',
        'product_id',
        'order_id',
    ];
    public function product(){
        return $this->belongsTo(Product::class, 'product_id');
    }
    public function order(){
        return $this->belongsTo(Order::class, 'order_id');
    }
    protected static function booted(): void
    {
        static::saving(function ($item) {
            if ($item->product) {
                $item->total_amount = $item->quantity * $item->product->price;
            }
        });
    }
}
