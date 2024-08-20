<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
        return response()->json(null,204);
    }
}
