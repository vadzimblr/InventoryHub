<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
      'address',
      'total_amount',
      'client_id',
      'order_status_id',
    ];
    public function client(){
        return $this->belongsTo(User::class, 'client_id');
    }
    public function orderStatusesHistory(){
        return $this->hasMany(OrderStatusesHistory::class,'order_status_id');
    }
}
