<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreOrderRequest;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created order in storage.
     * @param StoreOrderRequest - validate request
     * @return newly created order model
     */
    public function store(StoreOrderRequest $request)
    {
        // Retrieve data
        $product_id = $request->product_id;
        $user_id = Auth::id();

        // Check if the product exists
        $product = Product::find($product_id);

        if (!$product) {
            // Return a JSON response with an error message and a 404 status code
            return response()->json([
                'error' => 'Product not found'
            ], 404);
        }
        
        //Create new order
        $order = Order::create([
            'product_id'=> $product_id,
            'user_id' => $user_id
        ]);

        return $order;
    }


    /**
     * Remove the specified resource from storage.
     * @param string $id - order id
     * @return response code 204
     */
    public function destroy(string $id)
    {
         // Retrieve data
         $order_id = $id;
         $user_id = Auth::id();
 
         // Check if the product exists
         $order = Order::where('id',$order_id)->where('user_id',$user_id)->first();
            
         if (!$order) {
             // Return a JSON response with an error message and a 404 status code
             return response()->json([
                 'error' => 'Order not found'
             ], 404);
         }
         
         $order->delete();
         return response()->json(null,204);
    }
}
