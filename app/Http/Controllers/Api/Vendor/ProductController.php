<?php

namespace App\Http\Controllers\Api\Vendor;

use App\Actions\LocalFileUploader;
use App\Http\Controllers\Controller;
use App\Http\Requests\Vendor\Product\StoreProductRequest;
use App\Http\Requests\Vendor\Product\UpdateProductRequest;
use App\Http\Resources\ProductResource;
use App\Http\Responses\SuccessResponse;
use App\Models\Product;
use App\Models\Vendor;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redis;
use Illuminate\Validation\UnauthorizedException;
use Illuminate\Validation\ValidationException;

class ProductController extends Controller
{
    public function index(Vendor $vendor){
        $products = Cache::remember("vendor-{$vendor->id}-products", now()->addHours(5), function () use ($vendor) {
            return $vendor->products()->paginate();
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
        if(auth('vendor')->id() !== $product->category->vendor_id) {
            throw new UnauthorizedException;
        }
        return SuccessResponse::send('Product Details', ProductResource::make($product));
    }

    public function store(StoreProductRequest $request, LocalFileUploader $localFileUploader){
        $data = $request->validated();
        $data['image'] = $request->hasFile('image')
            ? $localFileUploader->upload($request->file('image'), 'products_images')
            : null;
        $product = Product::create($data);
        Cache::forget("vendor-{$product->category->vendor_id}-products");
        return SuccessResponse::send('Product Added Successfully', ProductResource::make($product), 201);
    }

    public function update(UpdateProductRequest $request, Product $product, LocalFileUploader $localFileUploader){
        $data = $request->validated();
        if($request->hasFile('image')){
            $data['image'] = $localFileUploader->upload($request->file('image'), 'products_images', $product->image ?? null);
        }
        $product->update($data);
        Cache::forget("vendor-{$product->category->vendor_id}-products");
        return SuccessResponse::send('Product Updated', ProductResource::make($product));
    }

    public function destroy(Product $product, LocalFileUploader $localFileUploader){
        $isVendorOwnProduct = auth('vendor')->user()->products()->find($product->id);
        if(!$isVendorOwnProduct) {
            throw new UnauthorizedException;
        }
        $product->delete();
        Cache::forget("vendor-{$product->category->vendor_id}-products");
        if(isset($product->image)) {
            $localFileUploader->deleteFile('products_images/' . $product->image);
        }
        return SuccessResponse::send('Product Deleted Successfully');
    }
}
