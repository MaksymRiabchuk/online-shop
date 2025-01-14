<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductReview;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

/**
 * @OA\Info(
 *     title="Wildpath API",
 *     version="0.1"
 * )
 */

class ProductController extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    /**
     * @OA\Get(
     *     path="/api/get-products",
     *     summary="Get list of products",
     *     tags={"Products"},
     *     @OA\Parameter(
     *         name="category_id",
     *         in="query",
     *         description="Category ID to filter products",
     *         required=false,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful response",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/Product")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="No products were found",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="message", type="string", example="No products were found")
     *         )
     *     )
     * )
     */
    public function getProducts(Request $request)
    {
        $category_id = $request->get('category_id', 0);
        if ($category_id == 0) {
            $data = Product::query()->orderBy('rate', 'desc')->get();
        } else {
            $data = Product::query()->where('category_id', $category_id)
                ->orderBy('rate', 'desc')->get();
        }
        if (count($data) > 0) {
            return response()->json([
                'data' => $data,
                'status' => 'success',
            ]);
        }
        return response()->json([
            'data' => $data,
            'status' => 'error',
            'message' => 'No products were found',
        ], 404);
    }


    public function getProduct(Request $request)
    {
        $slug = $request->get('slug');
        if (!$slug) {
            return response()->json([
                'data' => [],
                'status' => 'error',
                'message' => 'No product found',
            ], 404);
        }
        $product = Product::query()->where('slug', $slug)->first();
        return response()->json([
            'data' => $product,
            'status' => 'success',
        ]);
    }
    public function getReviews(Request $request)
    {
        $slug = $request->get('slug');
        if (!$slug || !Product::query()->where('slug', $slug)->exists()) {
            return response()->json([
                'data' => [],
                'status' => 'error',
                'message' => 'No product found',
            ], 404);
        }
        $product=Product::query()->where('slug', $slug)->first();
        $reviews=ProductReview::query()->where('product_id',$product->id)->orderBy('rate','desc')->get();
        if (count($reviews) > 0) {
            return response()->json([
                'data' => $reviews,
                'status' => 'success',
            ]);
        }
        return response()->json([
            'data' => [],
            'status' => 'error',
            'message' => 'No reviews found',
        ], 404);
    }
}
