<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bill extends Model
{
    protected $fillable = [
        'supplier_order_id',
        'paid_at',
        'accountant_id',
    ];
    public function accountant(){
        return $this->belongsTo(User::class,'accountant_id');
    }
    public function supplierOrder(){
        return $this->belongsTo(SupplierOrder::class,'supplier_order_id');
    }

}
