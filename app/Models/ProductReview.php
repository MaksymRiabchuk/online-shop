<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
/**
 * @property string $title The title of the review
 * @property string $review The message of the review
 * @property int $status The status of the review
 * @property int $product_id The product to which the review belongs
 * @property int $user_id The user who has written the review
 */
class ProductReview extends Model
{
    protected $fillable = ['title', 'review', 'rate', 'status', 'user_id', 'product_id'];

    public function product(){
        return $this->belongsTo(Product::class,'product_id');
    }

    public function user(){
        return $this->belongsTo(User::class,'user_id');
    }
}
