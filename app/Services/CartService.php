<?php

namespace App\Services;

use App\Exceptions\NotFoundException;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;

class CartService
{
    public function getCartForUser($userId)
    {
        return Cart::with('items.product')->firstOrCreate(['user_id' => $userId]);
    }

    public function addToCart($userId, $productId, $quantity = 1)
    {
        $cart = $this->getCartForUser($userId);

        $item = $cart->items()->where('product_id', $productId)->firstOrFail();

        if ($item) {
            $item->increment('quantity', $quantity);
        } else {
            $cart->items()->create([
                'product_id' => $productId,
                'quantity' => $quantity
            ]);
        }

        return $cart->fresh('items.product');
    }

    public function updateQuantity($userId, $productId, $newQuantity)
    {
        $cart = $this->getCartForUser($userId);

        $item = $cart->items()->where('product_id', $productId)->firstOrFail();

        if (!$item) {
            throw new NotFoundException();
        }

        if ($newQuantity <= 0) {
            $item->delete();
        } else {
            $item->update([ 'quantity' => $newQuantity ]);
        }

        return $cart->fresh('items.product');
    }

    public function removeFromCart($userId, $productId)
    {
        $cart = $this->getCartForUser($userId);

        $cart->items()->where('product_id', $productId)->delete();

        return $cart->fresh('items.product');
    }

    public function clearCart($userId)
    {
        $cart = $this->getCartForUser($userId);

        $cart->items()->delete();

        return $cart;
    }
}