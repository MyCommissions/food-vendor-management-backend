<?php

namespace App\Services;

use App\Models\Product;

class ProductService {

    public function createProduct(array $data, $storeId)
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

}