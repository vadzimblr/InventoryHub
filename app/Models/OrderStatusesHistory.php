<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderStatusesHistory extends Model
{
    protected $fillable = [
        'order_id',
        'updated_at',
        'status',
        'changed_by'
    ];
    public function order(){
        return $this->belongsTo(Order::class, 'order_id');
    }
    public function changedBy(){
        return $this->belongsTo(User::class, 'changed_by');
    }
    public function status()
    {
        return $this->belongsTo(OrderStatus::class, 'status');
    }

}
