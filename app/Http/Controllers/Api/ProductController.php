<?php

namespace App\Http\Controllers\Api;

use App\Actions\LocalFileUploader;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Http\Resources\ProductResource;
use App\Http\Responses\SuccessResponse;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request){
        $products = Product::when($request->name, function ($query, $name) {
            $query->where('name', 'like', "%$name%");
        })->paginate();
        
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
        if($request->hasFile('image')){
            $data['image'] = $localFileUploader->upload($request->file('image'), 'products_images', $product->image ?? null);
        }
        $product->update($data);
        return SuccessResponse::send('Product Updated', ProductResource::make($product));
    }

    public function destroy(Product $product, LocalFileUploader $localFileUploader){
        $product->delete();
        if(isset($product->image)) {
            $localFileUploader->deleteFile('products_images/' . $product->image);
        }
        return SuccessResponse::send('Product Deleted Successfully');
    }
}
