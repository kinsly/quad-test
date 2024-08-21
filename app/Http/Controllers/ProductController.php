<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Define a cache key
        $cacheKey = 'products.all';

        // Remembering all products on cache forever. 
        // Cache will be invalidated when new product is added or updated.
        $products = Cache::rememberForever($cacheKey, function () {
            return $products = Product::all();
        });

        return $products;
    }

    /**
     * Create new product item
     * @param request The request object containing the validated data.
     * @return response containing the created product.
     */
    public function store(StoreProductRequest $request)
    {
        // Retrieve the validated input data
        $validated = $request->validated();

        $product = Product::create($validated);

        // Invalidate the cache
        Cache::forget('products.all');
        
        return $product;
    }

    /**
     * Display requested product based on product id.
     * @param string $id The ID of the product to retrieve.
     * @return response containing the product model or an error message.
     */
    public function show(string $id)
    {
        $product = Product::find($id);
        if (!$product) {
            return response()->json([
                'error' => 'Product not found'
            ], 404);
        }
        return $product;
    }

    /**
     * Update the specified resource in storage.
     * @param request The request object containing the validated data.
     * @return update product model
     */
    public function update(UpdateProductRequest $request, string $id)
    {
        // Retrieve the validated input data
        $validated = $request->validated();

        // Find the product by its ID
        $product = Product::find($id);

        if (!$product) {
            return response()->json([
                'error' => 'Product not found'
            ], 404);
        }

        // Update the product with the validated data
        $product->update($validated);

        // Invalidate the cache
        Cache::forget('products.all');

        return $product;
    }

    /**
     * Remove the specified resource from storage.
     * @param string $id - ID of the product to delete
     * @return Response code 204
     */
    public function destroy(string $id)
    {
        // Find the product by its ID
        $product = Product::find($id);
        if (!$product) {
            return response()->json([
                'error' => 'Product not found'
            ], 404);
        }

        $product->delete();
        
        // Invalidate the cache
        Cache::forget('products.all');
        return response()->json(null,204);
    }
}
