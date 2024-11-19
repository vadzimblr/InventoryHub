<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bill extends Model
{
    protected $fillable = [
      'created_at',
      'order_id',
        'paid_at',
        'issued_at',

      'issued_by'
    ];
    public function order(){
        return $this->belongsTo(Order::class);
    }
    public function issuedBy(){
        return $this->belongsTo(User::class, 'issued_by');
    }
}
