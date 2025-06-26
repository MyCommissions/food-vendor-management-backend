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
        $user = Auth::user();

        $product = $this->productService->createProduct($request->validated(), $user);

        return response()->json([
            'product' => $product
        ], 201);

    }

    public function updateProduct(UpdateProductRequest $request, $productId)
    {
        $user = Auth::user();

        $updatedProduct = $this->productService->updateProduct($request->validated(),(int) $productId,$user);

        return response()->json([
            'product' => $updatedProduct
        ], 200);
    }

    public function deleteProduct($productId)
    {
        $user = Auth::user();

        $deletedProduct = $this->productService->removeProduct((int) $productId, $user);

        return response()->json([
            'product' => $deletedProduct
        ], 200);
    }

}
