<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function addtocart(Request $request)
    {
        if(auth('sanctum')->check())
        {
            $user_id = auth('sanctum')->user()->id;
            $product_id = $request->product_id;
            $product_quantity = $request->product_quantity;

            $productCheck = Product::where('id',$product_id)->first();
            if($productCheck)
            {
                if(Cart::where('product_id',$product_id)->where('user_id',$user_id)->exists())
                {
                    return response()->json([
                        'status' => 409,
                        'message' => $productCheck->name .'Already Added to Cart',
                    ]);
                }
                else
                {
                    $cartitem = new Cart;
                    $cartitem->user_id = $user_id;
                    $cartitem->product_id = $product_id;
                    $cartitem->product_quantity = $product_quantity;
                    $cartitem->save();

                    return response()->json([
                        'status' => 201,
                        'message' => 'Added To Cart',
                    ]);
                }
            }
            else
            {
                return response()->json([
                    'status' => 404,
                    'message' => 'Product Not Found',
                ]);
            }
        }
        else
        {
            return response()->json([
                'status' => 401,
                'message' => 'Login To Add To Cart',
            ]);
        }
    }

    public function viewcart()
    {
        if(auth('sanctum')->check())
        {
            $user_id = auth('sanctum')->user()->id;
            $cartitem = Cart::where('user_id',$user_id)->get();
            return response()->json([
                'status'=>200,
                'cart'=>$cartitem,
            ]);
            }
        else
        {
            return response()->json([
                'status' => 401,
                'message' => 'Login To Add To Cart',
            ]);
        }
    }
    public function updatequantity($cart_id,$scope)
    {
        if(auth('sanctum')->check())
        {
            $user_id = auth('sanctum')->user()->id;
            $cartitem = Cart::where('id',$cart_id)->where('user_id',$user_id)->first();
            if ($scope == "inc")
            {
                $cartitem->product_quantity  += 1;
            }
            else if ($scope == "dec")
            {
                $cartitem->product_quantity  -= 1;
            }
            $cartitem->update();
            return response()->json([
                'status' => 200,
                'message' => 'Quantity updated',
            ]);
        }
        else
        {
            return response()->json([
                'status' => 401,
                'message' => 'Login To Continue',
            ]);
        }
    }
    public function deleteCartitem($cart_id)
    {
        if(auth('sanctum')->check())
        {
            $user_id = auth('sanctum')->user()->id;
            $cartitem = Cart::where('id',$cart_id)->where('user_id',$user_id)->first();
            if($cartitem)
            {
                $cartitem->delete();
                return response()->json([
                    'status' => 200,
                    'message' => 'Cart Item Removed Successfully',
                ]);
            }
            else
            {
                return response()->json([
                    'status' => 404,
                    'message' => 'Cart Item Not Found',
                ]);
            }

        }
        else
        {
            return response()->json([
                'status' => 401,
                'message' => 'Login To Continue',
            ]);
        }
    }
}
