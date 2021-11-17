<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class OrdersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        {
            $orders = Order::with('user')->orderBy('id', 'DESC')->get();
            return response()->json($orders->toArray());
        }
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
            'user_id' => 'required|numeric|min:1',
            'price_in_PLN' => 'required|regex:/^\d+(\.\d{1,2})?$/',
            'order_status' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ], 400);
        }

        $order = new Order();
        $order->user_id = $request->user_id;
        $order->price_in_PLN = $request->price_in_PLN;
        $order->order_status = $request->order_status;

        if ($order->save()) {
            return response()->json([
                'status' => true,
                'order' => $order,
                'message' => 'New order created'
            ]);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Failed to create order'
            ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return Order
     */
    public function show(int $id): Order
    {
        return Order::find($id);

    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param int $id
     * @return Order
     */
    public function update(Request $request,int $id): Order
    {
        $order = Order::find($id);
        $order->update($request->all());
        return $order;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        $order = Order::find($id);

        if ($order->delete()) {
            return response()->json([
                'status' => true,
                'order' => $order,
                'message' => "Order deleted correctly"
            ]);
        } else {
            return response()->json([
                'status' => false,
                'message' => "Can't delete this order"
            ]);
        }
    }
}
