<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProductResource;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function index(Category $category)
    {
        return ProductResource::collection($category->products()->get());
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Category  $category
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show(Category $category, $id)
    {
        return new ProductResource($category->products()->findOrFail($id));
    }
}
