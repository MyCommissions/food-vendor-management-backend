<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Services\ProductService;
use App\Models\Store;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    protected $productService;
    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    public function getAllProducts()
    {
        $user = Auth::user();

        $products = $this->productService->allProducts($user);

        return response()->json([
            'products' => $products
        ], 200);
    }

    public function getProduct($productId)
    {
        $user = Auth::user();

        $product = $this->productService->product((int) $productId, $user);

        return response()->json([
            'product' => $product
        ], 200);
    }

    public function createProduct(CreateProductRequest $request)
    {
        $user = Auth::user()->id;

        $product = $this->productService->createProduct($request->validated(), $user);

        return response()->json([
            'product' => $product
        ], 201);

    }

    public function updateProduct(UpdateProductRequest $request, Product $product)
    {
        $userId = Auth::user()->id;

        $store = Store::where('user_id', $userId)->first();

        if (!$store) {
            return response()->json([
                'message' => 'Your account does not have an associated store.'
            ], 403);
        }

        if ($product->store_id !== $store->id) {
            return response()->json([
                'message' => 'You are not authorized to update this product.'
            ], 403);
        }

        $updatedProduct = $this->productService->updateProduct(
            $request->validated(),
            $product->id,
            $store->id
        );

        return response()->json([
            'product' => $updatedProduct
        ], 200);
    }

}
