<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes;
    use HasFactory;
    protected $fillable = [
        'name',
        'description',
        'price',
        'quantity',
        'unit',
        'is_whole_unit',
        'supplier_id',
        'created_by',
        'created_at',
    ];
    public $timestamps = true;
    const UPDATED_AT = null;
    public function supplier(){
        return $this->belongsTo(Supplier::class,'supplier_id');
    }
    public function creatorId(){
        return $this->belongsTo(User::class,'created_by');
    }
}
