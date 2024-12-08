<?php

namespace App\Http\Controllers\Api\Customer;

use App\Http\Controllers\Controller;
use App\Http\Requests\Customer\Cart\AddToCartRequest;
use App\Http\Requests\Customer\Cart\CheckoutRequest;
use App\Http\Resources\Customer\CartResource;
use App\Http\Resources\Customer\OrderResource;
use App\Http\Responses\SuccessResponse;
use App\Models\Cart;
use App\Models\Order;
use Illuminate\Validation\ValidationException;

class CartController extends Controller
{
    public function show() {
        $customer = auth('customer')->user();
        $cart = $customer->carts()->with(['items', 'items.product'])->where('status', 'active')->first();
        return SuccessResponse::send('Your Cart', CartResource::make($cart));
    }

    public function addToCart(AddToCartRequest $request){
        $customer_id = auth('customer')->id();
        $cartItemData = $request->validated();
        $cart = Cart::where('customer_id', $customer_id)
            ->where('vendor_id', $cartItemData['vendor_id'])
            ->where('status', 'active')
            ->first();

        if(!$cart){
            if(Cart::where('customer_id', $customer_id)->where('status', 'active')->exists()) {
                return ValidationException::withMessages([
                    'Clear cart first .. you can only order from one vendor'
                ]);
            }

            $cart = Cart::create([
                'customer_id' => $customer_id,
                'vendor_id' => $cartItemData['vendor_id'],
                'status' => 'active',
            ]);
        }

        $cartItem = $cart->items()
            ->where('product_id', $cartItemData['product_id'])
            ->first();

        if($cartItem) {
            $cartItem->update([
               'quantity' => $cartItem->quantity + $cartItemData['quantity'],
            ]);
        } else {
            $cart->items()->create([
                'product_id' => $cartItemData['product_id'],
                'quantity' => $cartItemData['quantity'],
            ]);
        }

        return SuccessResponse::send('Item added to cart successfully',CartResource::make($cart));
    }

    public function clearCart() {
        $cart = auth('customer')->user()->carts()->where('status', 'active')->first();
        $cart->items()->delete();
        $cart->delete();
        return SuccessResponse::send('Your cart has been cleared');
    }

    public function checkout(CheckoutRequest $request) {
        $customer = auth('customer')->user();
        $cart = $customer->carts()->where('status', 'active')->first();
        if(!$cart || $cart->items->isEmpty()){
            return ValidationException::withMessages([
                'Cart is empty'
            ]);
        }

        $totalPrice = $cart->items->sum(function ($item) {
            return $item->product->price * $item->quantity;
        });

        $order = Order::create([
            'customer_id' => $customer->id,
            'vendor_id' => $cart->vendor_id,
            'total_price' => $totalPrice,
            'payment_method' => $request->payment_method,
            'delivery_address' => $request->delivery_address,
            'delivery_fee' => 20,
            'status' => 'pending',
            'comments' => $request->comments
        ]);

        foreach($cart->items as $item){
            $order->items()->create([
                'product_id' => $item->product_id,
                'quantity' => $item->quantity,
                'unit_price' => $item->product->price,
                'total_price' => $item->product->price * $item->quantity,
            ]);
        }

        $cart->items()->delete();
        $cart->update([
            'status' => 'completed',
            'total_price' => $totalPrice
        ]);

        return SuccessResponse::send('Your order has been placed', OrderResource::make($order));
    }
}
