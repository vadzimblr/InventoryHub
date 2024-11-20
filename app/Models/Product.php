<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'description',
        'price',
        'quantity',
        'supplier_id',
        'created_by',
        'created_at',
    ];
    public function supplier(){
        return $this->belongsTo(Supplier::class,'supplier_id');
    }
    public function creatorId(){
        return $this->belongsTo(User::class,'created_by');
    }
}
