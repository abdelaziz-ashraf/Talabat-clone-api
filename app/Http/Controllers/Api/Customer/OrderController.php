<?php

namespace App\Http\Controllers\Api\Customer;

use App\Http\Controllers\Controller;
use App\Http\Resources\Customer\OrderDetailsResource;
use App\Http\Resources\Customer\OrderResource;
use App\Http\Responses\SuccessResponse;
use App\Models\Order;
use Illuminate\Validation\UnauthorizedException;

class OrderController extends Controller
{
    public function index () {
        $orders = auth('customer')->user()->orders()->paginate();
        return SuccessResponse::send('Your Orders', OrderResource::collection($orders), meta: [
            'pagination' => [
                'total' => $orders->total(),
                'current_page' => $orders->currentPage(),
                'per_page' => $orders->perPage(),
                'last_page' => $orders->lastPage(),
            ]
        ]);
    }

    public function show (Order $order) {
        if($order->customer_id !== auth('customer')->id()) {
            return new UnauthorizedException;
        }
        return SuccessResponse::send('Your Order', OrderDetailsResource::make($order));
    }
}
