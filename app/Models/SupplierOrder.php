<?php

namespace App\Models;

use App\Enums\OrderStatus;
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
}
