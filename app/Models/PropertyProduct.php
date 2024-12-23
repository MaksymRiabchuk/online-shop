<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $property_id The property
 * @property int $product_id The product
 */
class PropertyProduct extends Model
{
    protected $fillable = ['property_id', 'product_id'];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function property()
    {
        return $this->belongsTo(Property::class, 'property_id');
    }
}
