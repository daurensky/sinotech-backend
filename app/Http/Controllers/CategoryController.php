<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Http\Resources\CategoryResource;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class CategoryController extends Controller
{
    public function index(): AnonymousResourceCollection
    {
        $category = Category::query()
            ->with(['media', 'products'])
            ->orderBy('sort')
            ->get();

        return CategoryResource::collection($category);
    }

    public function show(string $slug): CategoryResource
    {
        $category = Category::query()
            ->where('slug', $slug)
            ->with(['media', 'products'])
            ->firstOrFail();

        return new CategoryResource($category);
    }
}
