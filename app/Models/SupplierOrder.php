<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SupplierOrder extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'supplier_id',
        'total_amount',
        'created_at',
        'manager_id',
        'order_status_id',
        'product_id',
        'quantity',
    ];
    public $timestamps = true;
    const UPDATED_AT = null;

    public function supplier(){
        return $this->belongsTo(Supplier::class, 'supplier_id');
    }

    public function product(){
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function manager(){
        return $this->belongsTo(User::class, 'manager_id');
    }

    public function orderStatus(){
        return $this->belongsTo(OrderStatus::class, 'order_status_id');
    }

    protected static function booted()
    {
        static::saving(function ($order) {
            $product = $order->product;
            $order->total_amount = $product->price * $order->quantity;
        });
    }
}
