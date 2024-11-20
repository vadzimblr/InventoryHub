<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $fillable = [
      'order_id',
        'paid_at',
        'created_at',
      'issued_by'
    ];
    public function order(){
        return $this->belongsTo(Order::class,'order_id');
    }
    public function issuedBy(){
        return $this->belongsTo(User::class, 'issued_by');
    }
}
