<?php

namespace App\Http\Controllers\Api\Vendor;

use App\Http\Controllers\Controller;
use App\Http\Requests\Vendor\ChangeOrederStatusRequest;
use App\Http\Requests\Vendor\SearchByStatusRequest;
use App\Http\Resources\Vendor\ListOrdersResource;
use App\Http\Resources\Vendor\OrderDetailsResource;
use App\Http\Responses\SuccessResponse;
use App\Models\Order;
use Illuminate\Validation\UnauthorizedException;

class OrderController extends Controller
{

    public function index(SearchByStatusRequest $request) {
        $orders = Order::where('vendor_id', auth('vendor')->id())
            ->when($request->status, function ($query) use ($request) {
                $query->where('status', $request->status);
            })
            ->get();
        return SuccessResponse::send('Orders List', ListOrdersResource::collection($orders));
    }

    public function show(Order $order) {
        if($order->vendor_id != auth('vendor')->id()) {
            throw new UnauthorizedException;
        }
        return SuccessResponse::send('Order Details', OrderDetailsResource::make($order));
    }

    public function changeStatus(ChangeOrederStatusRequest $request, Order $order) {
        $order->update([
            'status' => $request->status
        ]);
        return SuccessResponse::send('Order Status Changed', $order);
    }
}
