<?php

namespace App\Http\Controllers\Api;

use App\Actions\LocalFileUploader;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Http\Resources\ProductResource;
use App\Http\Responses\SuccessResponse;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index(){
        $products = Product::paginate();
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
        return SuccessResponse::send('Product Details', ProductResource::make($product));
    }

    public function store(StoreProductRequest $request, LocalFileUploader $localFileUploader){
        $data = $request->validated();
        $data['image'] = $request->hasFile('image')
            ? $localFileUploader->upload($request->file('image'), 'products_images')
            : null;
        $product = Product::create($data);
        return SuccessResponse::send('Product Added Successfully', ProductResource::make($product), 201);
    }

    public function update(UpdateProductRequest $request, Product $product, LocalFileUploader $localFileUploader){
        $data = $request->validated();
        // todo : Update Image ..
        $product->update($data);
        return SuccessResponse::send('Product Updated', ProductResource::make($product));
    }

    public function destroy(Product $product){
        $product->delete();
        // todo : delete image
        return SuccessResponse::send('Product Deleted Successfully');
    }
}
