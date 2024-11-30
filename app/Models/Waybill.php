<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Waybill extends Model
{
    protected $fillable = [
        'invoice_id',
        'storekeeper_id',
        'handoff_time',
    ];
    public $timestamps = false;
    protected $casts = [
        'handoff_time' => 'datetime',
    ];
    public function storekeeper(){
        return $this->belongsTo(User::class, 'storekeeper_id');
    }
    public function bill(){
        return $this->belongsTo(Invoice::class, 'invoice_id');
    }
}
