<?php

namespace App\Services;

use App\Models\Product;
use App\Models\Store;
use App\Models\User;
use Illuminate\Http\Client\HttpClientException;

class ProductService 
{
    public function allProducts(User $user)
    {
        if (!$user->isVendor()) {
            throw new HttpClientException(
                response()->json([
                    'message' => 'Not Authorized.'
                ], 403)
            );
        }

        $storeId = Store::where('user_id', $user->id)
            ->firstOrFail();

        return Product::where('store_id', $storeId)->all();
    }

    public function product(int $productId, User $user)
    {
        if (!$user->isVendor()) {
            throw new HttpClientException(
                response()->json([
                    'message' => 'Not Authorized.'
                ], 403)
            );
        }

        $storeId = Store::where('user_id', $user->id)
            ->firstOrFail();

        return Product::where('id', $productId)
            ->where('store_id', $storeId)
            ->firstOrFail();
    }

    public function createProduct(array $data, int $storeId)
    {
        return Product::create([
            'store_id' => $storeId,
            'name' => $data['name'],
            'description' => $data['description'],
            'category' => $data['category'],
            'price' => $data['price'],
            'image_path' => $data['image_path']
        ]);
    }

    public function updateProduct(array $data, int $productId, int $storeId)
    {
        $product = Product::where('id',$productId)
            ->where('store_id', $storeId)
            ->firstOrFail();

        $product->update($data);

        return $product;
    }

}