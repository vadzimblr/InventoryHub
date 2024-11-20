<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SupplierOrderItem extends Model
{
    protected $fillable = [
        'quantity',
        'unit',
        'is_whole_unit',
        'total_amount',
        'product_id',
        'supplier_order_id',
    ];
    public function product(){
        return $this->belongsTo(Product::class,'product_id');
    }
    public function supplierOrder(){
        return $this->belongsTo(SupplierOrder::class,'supplier_order_id');
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
