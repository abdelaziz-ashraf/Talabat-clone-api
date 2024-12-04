<?php

namespace App\Http\Controllers\Api\Vendor;

use App\Http\Controllers\Controller;
use App\Http\Requests\Vendor\Category\StoreCategoryRequest;
use App\Http\Requests\Vendor\Category\UpdateCategoryRequest;
use App\Http\Resources\CategoryResource;
use App\Http\Responses\SuccessResponse;
use App\Models\Category;
use Illuminate\Validation\UnauthorizedException;
use Illuminate\Validation\ValidationException;

class CategoryController extends Controller
{

    public function index(){
        $categories = auth('vendor')->user()->categories()->paginate();
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
        if($category->vendor_id != auth('vendor')->id()){
            throw new UnauthorizedException;
        }
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
        if($category->vendor_id != auth('vendor')->id()){
            throw new UnauthorizedException;
        }
        $category->delete();
        return SuccessResponse::send('Category Deleted');
    }
}
