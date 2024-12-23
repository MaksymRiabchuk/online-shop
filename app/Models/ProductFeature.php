<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property string $name Name of the feature
 * @property int $product_id The product to which the feature belongs to
 */
class ProductFeature extends Model
{
    protected $fillable = ['name', 'product_id'];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
