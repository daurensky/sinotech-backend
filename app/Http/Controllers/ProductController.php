<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Http\Resources\ProductResource;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ProductController extends Controller
{
    public function index(): AnonymousResourceCollection
    {
        $category = Product::query()
            ->with(['media', 'category'])
            ->orderBy('name')
            ->get();

        return ProductResource::collection($category);
    }

    public function show(string $slug): ProductResource
    {
        $category = Product::query()
            ->where('slug', $slug)
            ->with(['media', 'category'])
            ->firstOrFail();

        return new ProductResource($category);
    }
}
