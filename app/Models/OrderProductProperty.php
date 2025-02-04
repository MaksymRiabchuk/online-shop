<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderProductProperty extends Model
{
    use HasFactory;


    protected $fillable = ['order_product_id', 'property_id', 'value_id'];


    public function orderProduct() : BelongsTo
    {
        return $this->belongsTo(OrderProduct::class, 'order_product_id');
    }


    public function property() : BelongsTo
    {
        return $this->belongsTo(Property::class, 'property_id');
    }


    public function value() : BelongsTo
    {
        return $this->belongsTo(PropertyValue::class, 'value_id');
    }

}
