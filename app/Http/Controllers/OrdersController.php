<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
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
        Log::channel('order')->info('Get all orders', [
            'listOfOrder' => Order::all()
        ]);
        $orders = Order::with('user')->orderBy('id', 'DESC')->get();
        return response()->json($orders->toArray());

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
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function storePublic(Request $request): JsonResponse
    {

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:50',
            'email' => 'required|email',
            'product_id' => 'required|numeric|min:0'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ], 400);
        }

        // Create new user or update existing one after placing order
        $user = User::where('email', $request->email)->first();
        if ($user == null) {
            $user = new User();
            $user->name = $request->name;
            $user->email = $request->email;
            $user->number_of_orders = 1;
            $user->save();
        } else {
            $user->number_of_orders = $user->orders()->count();
            $user->update();
        }

        $newProduct = Product::find($request->product_id);

        $openOrder = Order::where('user_id', $user->id)->where('order_status', 'open')->first();
        $closedOrders = Order::where('user_id', $user->id)->where('order_status', 'close')->get();
        $userOrders = Order::where('user_id', $user->id)->get();


        Log::channel('order')->info('Order created', [
            'orderCreated' => Order::where('user_id', $user->id)->get()
        ]);

        //Count total amount of ordered products
        if ($userOrders->count() >=1 ){
            $totalPrice = $newProduct->price_in_PLN;
        } else {
            $totalPrice = 0;
        }

        foreach ($userOrders as $userOrder) {
            foreach ($userOrder->products as $product){
                $totalPrice += $product->price_in_PLN;
            }
        }

        //Create new order if all order statuses are closed
        if ($user->id && $closedOrders && $openOrder == null) {
            $order = new Order();
            $order->user_id = $user->id;
            $order->price_in_PLN = $newProduct->price_in_PLN;
            $order->order_status = $request->order_status;
            $order->save();
            DB::table('order_product')->insert([
                'order_id' => $order->id,
                'product_id' => $request->product_id,
                'created_at' => date('Y-m-d H:i:s'),
            ]);
            return response()->json([
                'status' => true,
                'order' => $order,
                'message' => 'New order created'
            ]);

            //Update existing order if order status is  open
        } elseif ($user->id && $openOrder->count() >= 1 ) {
            $openOrder->user_id = $user->id;
            $openOrder->price_in_PLN = $totalPrice;
            $openOrder->order_status = $request->order_status;
            $openOrder->update();
            DB::table('order_product')->insert([
                'order_id' => $openOrder->id,
                'product_id' => $request->product_id,
                'created_at' => date('Y-m-d H:i:s'),
            ]);
            return response()->json([
                'status' => true,
                'order' => $openOrder,
                'message' => 'New order updated'
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
         * @return mixed
         */
        public
        function show(int $id)
        {
            return Order::where('id', $id)->with('products')->get();
        }

        /**
         * Update the specified resource in storage.
         *
         * @param Request $request
         * @param int $id
         * @return Order
         */
        public
        function update(Request $request, int $id): Order
        {
            Log::channel('order')->info('Order updated', [
                'orderUpdated' => Order::find($id)
            ]);
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
        public
        function destroy(int $id): JsonResponse
        {
            $order = Order::find($id);

            Log::channel('order')->info('Order destroyed', [
                'orderDestroyed' => Order::find($id)
            ]);

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
