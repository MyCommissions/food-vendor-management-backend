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

        if (!$storeId) {
            throw new HttpClientException(
                response()->json([
                    'message' => 'Store not found or you are not authorize to access this.'
                ], 404)
            );
        }

        $product = Product::where('id', $productId)
            ->where('store_id', $storeId)
            ->firstOrFail();

        if (!$product) {
            throw new HttpClientException(
                response()->json([
                    'message' => 'Product not found or you are not authorize to access this.'
                ], 404)
            );
        }

        return $product;
    }

    public function createProduct(array $data, User $user)
    {
        if($user->isVendor()) {
            throw new HttpClientException(
                response()->json([
                    'message' => 'Not Authorized.'
                ], 403)
            );
        }

        $store = Store::where('user_id', $user->id)->first();

        if (!$store) {
            throw new HttpClientException(
                response()->json([
                    'message' => 'Store not found or you are not authorize to access this.'
                ], 404)
            );
        }

        $product = Product::where('store_id', $store->id)->exists();

        if ($product) {
            throw new HttpClientException(
                response()->json([
                    'message' => 'Product already existed on this Store.'
                ], 403)
            );
        }

        return Product::create([
            'store_id' => $store->id,
            'name' => $data['name'],
            'description' => $data['description'],
            'category' => $data['category'],
            'price' => $data['price'],
            'image_path' => $data['image_path']
        ]);
    }

    public function updateProduct(array $data, int $productId, User $user)
    {
        $store = Store::where('user_id', $user->id)->first();

        if (!$store) {
            throw new HttpClientException(
                response()->json([
                    'message' => 'Store not found or you are not authorized to access this.'
                ], 404)
            );
        }

        $product = Product::where('id',$productId)
            ->where('store_id', $store->id)
            ->firstOrFail();

        if (!$product) {
            throw new HttpClientException(
                response()->json([
                    'message' => 'Product not found or you are not authorize to access this.'
                ], 404)
            );
        }

        if ($product->store_id !== $store->id) {
            return response()->json([
                'message' => 'You are not authorized to update this product.'
            ], 404);
        }

        $product->update($data);

        return $product;
    }

    public function removeProduct(int $productId, User $user)
    {
        $store = Store::where('user_id', $user->id)->first();

        if (!$store) {
            throw new HttpClientException(
                response()->json([
                    'message' => 'Store not found or you are not authorized to access this.'
                ], 404)
            );
        }

        $product = Product::where('id', $productId)
            ->where('store_id', $store->id)
            ->firstOrFail();

        if (!$product) {
            throw new HttpClientException(
                response()->json([
                    'message' => 'Product not found or you are not authorize to access this.'
                ], 404)
            );
        }

        if ($product->store_id !== $store->id) {
            return response()->json([
                'message' => 'You are not authorized to update this product.'
            ], 404);
        }

        $product->delete();

        return $product;
    }

}