<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Http\Resources\CategoryResource;
use App\Http\Responses\SuccessResponse;
use App\Models\Category;
use App\Models\Vendor;

class CategoryController extends Controller
{

    public function getVendorCategories(Vendor $vendor){
        $categories = $vendor->categories()->paginate();
        return SuccessResponse::send('Categories List', CategoryResource::collection($categories), meta:[
            'pagination' => [
                'total' => $categories->total(),
                'per_page' => $categories->perPage(),
                'current_page' => $categories->currentPage(),
                'last_page' => $categories->lastPage(),
            ]
        ]);
    }

    public function show(Category $category){
        return SuccessResponse::send('Category Details', CategoryResource::make($category));
    }

    public function store(StoreCategoryRequest $request){
        $category = Category::create([
            'name' => $request->name,
            'vendor_id' => auth('vendor')->id()
        ]);
        return SuccessResponse::send('Category Created', CategoryResource::make($category), 201);
    }

    public function update(UpdateCategoryRequest $request, Category $category){
        $category->update($request->validated());
        return SuccessResponse::send('Category Updated', CategoryResource::make($category));
    }

    public function destroy(Category $category){
        $category->delete();
        return SuccessResponse::send('Category Deleted');
    }
}
