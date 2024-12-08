<?php

namespace App\Http\Controllers\Api\Customer;

use App\Http\Controllers\Controller;
use App\Http\Requests\Customer\Cart\ChangeCartItemQuantityRequest;
use App\Http\Responses\SuccessResponse;
use App\Models\CartItem;
use Illuminate\Validation\UnauthorizedException;

class CartItemController extends Controller
{
    public function changeQuantity(ChangeCartItemQuantityRequest $request, CartItem $cartItem) {
        $cartItem->update([
            'quantity' => $request->get('quantity'),
        ]);

        return SuccessResponse::send('Quantity updated successfully', $cartItem->cart()->with(['items', 'items.product'])->get());
    }

    public function destroy(CartItem $cartItem) {
        if($cartItem->cart->customer_id !== auth('customer')->id()) {
            return new UnauthorizedException;
        }
        $cartItem->delete();
        return SuccessResponse::send('Item deleted successfully');
    }
}
