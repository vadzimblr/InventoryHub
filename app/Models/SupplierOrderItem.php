<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SupplierOrderItem extends Model
{
    protected $fillable = [
        'quantity',
        'total_price',
        'product_id',
        'supplier_order_id',
    ];
    public function product(){
        return $this->belongsTo(Product::class,'product_id');
    }
    public function supplierOrder(){
        return $this->belongsTo(SupplierOrder::class,'supplier_order_id');
    }
}
