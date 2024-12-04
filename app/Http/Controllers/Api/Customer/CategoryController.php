<?php

namespace App\Http\Controllers\Api\Customer;

use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\Customer\ViewCategoryResource;
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
            return SuccessResponse::send('Category Details', ViewCategoryResource::make($category));
    }

}
