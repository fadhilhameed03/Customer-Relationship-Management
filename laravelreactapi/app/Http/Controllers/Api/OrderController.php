<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::all();
        return response()->json([
            'status' => 200,
            'orders' => $orders,
        ]);
    }

    public function vieworder($id)
    {
        $order = Order::find($id);
        $orderItems = $order->orderitems;
        $items = [];
        foreach ($orderItems as $key => $item) {
            $items[$key]['id'] = $item->id;
            $items[$key]['quantity'] = $item->quantity;
            $items[$key]['price'] = $item->price;
            $product = Product::find($item->product_id);
            $items[$key]['product'] = $product->name;
            $items[$key]['image'] = $product->image;
        }
        if ($order)
        {
            return response() ->json([
                'status'=> 200,
                'order' => $order,
                'items' => $items
            ]);
        }
        else
        {
            return response() ->json([
                'status'=>404,
                'message'=>'No Order Found',
            ]);
        }
    }

    public function customerOrders($id)
    {
        $orders = Order::where('user_id', $id)->get();
        $user = User::find($id);
        $customerName = $user->firstname . ' ' . $user->lastname;
        if ($orders)
        {
            return response() ->json([
                'status' => 200,
                'orders' => $orders,
                'customerName' => $customerName
            ]);
        }
        else
        {
            return response() ->json([
                'status' => 404,
                'message'=> 'No Orders Found',
            ]);
        }
    }
}
