<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;

class FrontendController extends Controller
{
    public function category()
    {
        $category = Category::where('status','1')->get();
        return response()->json([
            'status'=>200,
            'category'=>$category,
        ]);
    }

    public function fetchproducts($slug)
    {
        $category = Category::where('slug' ,$slug)->where('status','1')->first();
        if ($category)
        {
            $product = Product::where('category_id',$category->id)->where('status','0')->get();
            if ($product)
            {
                return response()->json([
                    'status'=>200,
                    'product_data'=>[
                        'product'=>$product,
                        'category'=>$category,
                    ],
                ]);
            }
            else
            {
                return response()->json([
                    'status'=>400,
                    'category'=>'No Product Available',
                ]);
            }
        }
        else
        {
            return response()->json([
                'status'=>404,
                'category'=>'No Such Category Found',
            ]);
        }
    }

    public function viewproduct($category_slug,$product_slug)
    {
        $category = Category::where('slug' , $category_slug)->where('status','1')->first();
        if ($category)
        {
            $product = Product::where('category_id',$category->id)->where('slug',$product_slug)->where('status','0')->first();
            if($product)
            {
                return response()->json([
                    'status'=>200,
                    'product_data'=>[
                        'product'=>$product,
                    ],
                ]);
            }
            else
            {
                return response()->json([
                    'status'=>400,
                    'category'=>'No Product Available',
                ]);
            }
        }
        else
        {
            return response()->json([
                'status'=>404,
                'category'=>'No Such Category Found',
            ]);
        }
    }
}
