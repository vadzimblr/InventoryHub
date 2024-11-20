<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Waybill extends Model
{
    protected $fillable = [
        'bill_id',
        'storekeeper_id',
        'handoff_time',
    ];
    public function storekeeper(){
        return $this->belongsTo(User::class, 'storekeeper_id');
    }
    public function bill(){
        return $this->belongsTo(Bill::class, 'bill_id');
    }
}
