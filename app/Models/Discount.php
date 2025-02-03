<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Discount extends Model
{
    protected $fillable = ['product_id','start_date','end_date','percentage'];

    public function product(){
        return $this->belongsTo(Product::class,'product_id');
    }
}
