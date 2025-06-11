<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateProductRequest;
use App\Services\ProductService;
use App\Models\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    protected $productService;
    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    public function createProduct(CreateProductRequest $request)
    {
        $userId = Auth::user()->id;

        $storeId = Store::where('user_id', $userId);

        $product = $this->productService->createProduct($request->all(), $storeId);

        return response()->json([
            'product' => $product
        ], 201);

    }

}
