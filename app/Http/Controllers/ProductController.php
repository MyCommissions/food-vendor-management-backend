<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Services\ProductService;
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
        try {
            $user = Auth::user();

            $products = $this->productService->allProducts($user);

            return response()->json([
                'products' => $products
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function getProduct($productId)
    {
        try {
            $user = Auth::user();

            $product = $this->productService->product((int) $productId, $user);

            return response()->json([
                'product' => $product
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function createProduct(CreateProductRequest $request)
    {
        try {
            $user = Auth::user();

            $product = $this->productService->createProduct($request->validated(), $user);

            return response()->json([
                'product' => $product
            ], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function updateProduct(UpdateProductRequest $request, $productId)
    {
        try {
            $user = Auth::user();

            $updatedProduct = $this->productService->updateProduct($request->validated(), (int) $productId, $user);

            return response()->json([
                'product' => $updatedProduct
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function deleteProduct($productId)
    {
        try {
            $user = Auth::user();

            $deletedProduct = $this->productService->removeProduct((int) $productId, $user);

            return response()->json([
                'product' => $deletedProduct
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function productsByCategory($productId, $storeId)
    {
        try {
            $user = Auth::user();

            $products = $this->productService->productsByCategory((int) $productId, (int) $storeId, $user);

            return response()->json([
                'products' => $products
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
