<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderStatusesHistory extends Model
{
    protected $fillable = [
        'order_id',
        'updated_at',
        'status',
    ];
    public function order(){
        return $this->belongsTo(Order::class);
    }

}
