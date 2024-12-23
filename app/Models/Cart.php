<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $user_id User to whom this cart belongs
 * @property int $product_id The product that added to cart
 * @property int $quantity Quantity of the product
 * @property float $const The cost of each amount of products
 */
class Cart extends Model
{
    protected $fillable = ['user_id', 'product_id', 'quantity', 'cost'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
