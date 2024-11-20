<?php

namespace App\Models;

use App\Enums\OrderStatus;
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
    public function client(){
        return $this->belongsTo(User::class, 'client_id');
    }
    public function currentStatus(){
        return $this->belongsTo(OrderStatus::class,'order_status_id');
    }
    public function orderStatusHistory(){
        return $this->hasMany(OrderStatusesHistory::class,'order_id');
    }
}
