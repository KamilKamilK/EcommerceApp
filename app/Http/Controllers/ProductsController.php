<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class ProductsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        Log::channel('product')->info('Get all products',[
            'listOfProducts' => Product::first()
        ]);

        $products = DB::table('products')->orderBy('id', 'DESC')->get();
        return response()->json($products->toArray());

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:50|unique:products',
            'price_in_PLN' => 'required|regex:/^\d+(\.\d{1,2})?$/'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ], 400);
        }

        Log::channel('product')->info('Product created',[
            'productCreated' => Product::first()
        ]);

        $product = new Product();
        $product->name = $request->name;
        $product->price_in_PLN = $request->price_in_PLN;

        if ($product->save()) {
            return response()->json([
                'status' => true,
                'product' => $product,
                'message' => 'New product created'
            ]);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Failed to create product'
            ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return Product
     */
    public
    function show(int $id): Product
    {
        return Product::find($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param int $id
     * @return Product
     */
    public
    function update(Request $request, int $id): Product
    {
        Log::channel('product')->info('Product updated',[
            'productUpdated' => Product::find($id)
        ]);
        $product = Product::find($id);
        $product->update($request->all());
        return $product;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return JsonResponse
     */
    public
    function destroy(int $id): JsonResponse
    {

        Log::channel('product')->info('Product destroyed',[
            'productDestroyed' => Product::find($id)
        ]);
        $product = Product::find($id);

        if ($product->delete()) {
            return response()->json([
                'status' => true,
                'product' => $product,
                'message' => "Product deleted correctly"
            ]);
        } else {
            return response()->json([
                'status' => false,
                'message' => "Can't delete this product"
            ]);
        }
    }
}
