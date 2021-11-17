<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class OrdersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        {
            $orders = Order::with('user')->orderBy('id', 'DESC')->get();
            return response()->json($orders->toArray());
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'price_in_PLN' => 'required|between:0,99999.99',
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
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return Order::find($id);

    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $order = Order::find($id);
        $order->update($request->all());
        return $order;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
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
