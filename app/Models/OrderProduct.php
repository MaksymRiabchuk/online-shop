<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $order_id The order to which it belongs
 * @property int $product_id The product to which it belongs
 * @property int $quantity The quantity of the product
 * @property float $cost Cost of ordered products
 */
class OrderProduct extends Model
{
    protected $fillable = ['cost', 'order_id', 'product_id', 'quantity'];
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }
}
