<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $product_id The product to which the image belongs
 * @property string $image Path to the image
 * @property int $order The position among the other images belonged to one certain product
 * @property boolean $is_main Image is main or not
 */
class ProductImage extends Model
{
    protected $fillable = ['product_id', 'image', 'order', 'is_main'];
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
