<?php

namespace App\Services;

use App\Exceptions\ProductAlreadyExistsException;
use App\Exceptions\UnauthorizedAccessException;
use App\Models\Product;
use App\Models\Store;
use App\Models\User;
use Illuminate\Http\Client\HttpClientException;

class ProductService 
{
    public function allProducts(User $user)
    {
        if (!$user) {
            throw new UnauthorizedAccessException();
        }

        $storeId = Store::where('user_id', $user->id)
            ->firstOrFail();

        return Product::where('store_id', $storeId)->get();
    }

    public function product(int $productId, User $user)
    {
        if (!$user) {
            throw new UnauthorizedAccessException();
        }

        $storeId = Store::where('user_id', $user->id)
            ->firstOrFail();

        return Product::where('id', $productId)
            ->where('store_id', $storeId)
            ->firstOrFail();
    }

    public function createProduct(array $data, User $user)
    {
        if(!$user->isAdmin() || !$user->isVendor()) {
            throw new UnauthorizedAccessException();
        }

        $store = Store::where('user_id', $user->id)->firstOrFail();

        $product = Product::where('store_id', $store->id)->exists();

        if ($product) {
            throw new ProductAlreadyExistsException();
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
        if (!$user->isAdmin() || !$user->isVendor()) {
            throw new UnauthorizedAccessException();
        }

        $store = Store::where('user_id', $user->id)->firstOrFail();

        $product = Product::where('id',$productId)
            ->where('store_id', $store->id)
            ->firstOrFail();

        if ($product->store_id !== $store->id) {
            throw new UnauthorizedAccessException();
        }

        $product->update($data);

        return $product;
    }

    public function removeProduct(int $productId, User $user)
    {
        if (!$user->isAdmin() || !$user->isVendor()) {
            throw new UnauthorizedAccessException();
        }

        $store = Store::where('user_id', $user->id)->firstOrFail();

        $product = Product::where('id', $productId)
            ->where('store_id', $store->id)
            ->firstOrFail();

        if ($product->store_id !== $store->id) {
            throw new UnauthorizedAccessException();
        }

        $product->delete();

        return $product;
    }

    public function productsByCategory(string $category, int $storeId, User $user)
    {
        if (!$user) {
            throw new UnauthorizedAccessException();
        }

        return Product::where('category', $category)
            ->where('store_id', $storeId)
            ->get();
    }

}