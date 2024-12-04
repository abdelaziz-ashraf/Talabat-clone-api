<?php

namespace App\Http\Controllers\Api\Customer;

use App\Http\Controllers\Controller;
use App\Http\Resources\Customer\ViewProductResource;
use App\Http\Resources\ProductResource;
use App\Http\Responses\SuccessResponse;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class ProductController extends Controller
{
    public function index(Request $request){
        $customer_id = auth('customer')->id();
        $products = Cache::remember("products-{$customer_id}}", now()->addHours(5), function() use ($request){
            return Product::when($request->name, function ($query, $name) {
                $query->where('name', 'like', "%$name%");
            })->paginate();
        });

        return SuccessResponse::send('All Products', ProductResource::collection($products), meta: [
            'pagination' => [
                'total' => $products->total(),
                'current_page' => $products->currentPage(),
                'per_page' => $products->perPage(),
                'last_page' => $products->lastPage(),
            ]
        ]);
    }

    public function show(Product $product){
        return SuccessResponse::send('Product Details', ViewProductResource::make($product));
    }

}
