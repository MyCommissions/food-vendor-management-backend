<?php

namespace App\Services;

use App\Models\Product;

class ProductService {

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