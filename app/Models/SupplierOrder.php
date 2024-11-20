<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SupplierOrder extends Model
{
    protected $fillable = [
        'supplier_id',
        'total_amount',
        'created_at',
        'manager_id',
        'order_status_id',
    ];
    public function supplier(){
        return $this->belongsTo(Supplier::class,'supplier_id');
    }
    public function manager(){
        return $this->belongsTo(User::class,'manager_id');
    }
    public function orderStatus(){
        return $this->belongsTo(OrderStatus::class,'order_status_id');
    }
    protected static function booted()
    {
        static::saving(function ($order) {
            $totalAmount = $order->supplierOrderItems->sum(function ($item) {
                return $item->total_amount;
            });

            $order->total_amount = $totalAmount;
        });
    }
}
