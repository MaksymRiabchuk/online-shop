<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property string $name Title of the product
 * @property string $slug Slug of the product
 * @property int $category_id Category of the product
 * @property string $vendor_code Vendor code of the product
 * @property float $price Price of the product
 * @property string $description Description of the product
 * @property string $shipping Product`s shipping description
 * @property string $guarantee Description of guarantee of the product
 * @property int $rate Rate of the product
 * @property int $brand_id Brand of the product
 * @property string $category_name Name of the category of the product
 * @property string $brand_name Name of the brand of the product
 */

/**
 * @OA\Schema(
 *     schema="Product",
 *     type="object",
 *     title="Product",
 *     description="Product model",
 *     properties={
 *         @OA\Property(
 *             property="id",
 *             type="integer",
 *             description="ID of the product"
 *         ),
 *         @OA\Property(
 *             property="name",
 *             type="string",
 *             description="Name of the product"
 *         ),
 *         @OA\Property(
 *              property="slug",
 *              type="string",
 *              description="Slug of the product"
 *         ),
 *         @OA\Property(
 *               property="category_id",
 *               type="integer",
 *               description="Category of the product"
 *          ),
 *         @OA\Property(
 *               property="vendor_code",
 *               type="string",
 *               description="Vendor code of the product"
 *          ),
 *         @OA\Property(
 *               property="description",
 *               type="string",
 *               description="Description of the product"
 *          ),
 *         @OA\Property(
 *                property="shipping",
 *                type="string",
 *                description="Shipping description of the product"
 *           ),
 *         @OA\Property(
 *                property="guarantee",
 *                type="string",
 *                description="Guarentee description of the product"
 *         ),
 *         @OA\Property(
 *                property="rate",
 *                type="integer",
 *                description="rate of the product"
 *         ),
 *         @OA\Property(
 *                property="brand_id",
 *                type="integer",
 *                description="Brand of the product"
 *         ),
 *         @OA\Property(
 *             property="price",
 *             type="number",
 *             format="float",
 *             description="Price of the product"
 *         )
 *     }
 * )
 */
class Product extends Model
{

    protected $fillable = ['name', 'slug', 'category_id', 'vendor_code', 'price',
        'description', 'shipping', 'guarantee', 'rate', 'brand_id'];
    protected $appends = ['category_name', 'brand_name'];

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class, 'brand_id');
    }

    public static function getProductsList($json)
    {
        if ($json) {
            return response()->json(self::get()->toArray());
        }
        return self::get()->toArray();
    }


    public function getCategoryNameAttribute()
    {
        return $this->category()->first()->name;
    }

    public function getBrandNameAttribute()
    {
        return $this->brand()->first()->name;
    }

    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class);
    }

    public function imagesByMain(): HasMany
    {
        return $this->hasMany(ProductImage::class)->where('is_main', 1);
    }

    public function features(): HasMany
    {
        return $this->hasMany(ProductFeature::class);
    }
}
