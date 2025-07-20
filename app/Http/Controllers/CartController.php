<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddToCartRequest;
use App\Http\Requests\UpdateQuantityCartRequest;
use Illuminate\Http\Request;
use App\Services\CartService;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    protected $cartService;

    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
    }

    public function index()
    {
        $cart = $this->cartService->getCartForUser(Auth::id());

        return response()->json($cart);
    }

    public function add(AddToCartRequest $addToCartRequest)
    {
        $cart = $this->cartService->addToCart(Auth::id(), $addToCartRequest->validated()->product_id, $addToCartRequest->validated()->quantity ?? 1);

        return response()->json($cart);
    }

    public function updateQuantity(UpdateQuantityCartRequest $updateQuantityCart, $productId)
    {
        try {
            $cart = $this->cartService->updateQuantity(Auth::id(), $productId, $updateQuantityCart->quantity->validated());

            return response()->json($cart);
        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage()], 404);
        }
    }

    public function remove($productId)
    {
        $cart = $this->cartService->removeFromCart(Auth::id(), $productId);

        return response()->json($cart);
    }

    public function clear()
    {
        $cart = $this->cartService->clearCart(Auth::id());

        return response()->json($cart);
    }
}
