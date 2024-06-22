<?php

namespace App\Http\Controllers\API;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::all();
        return response() ->json([
            'status'=>200,
            'products'=>$products
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'category_id'=>'required|max:191',
            'slug'=>'required|max:191',
            'name'=>'required|max:191',
            'meta_title'=>'required|max:191',
            'brand'=>'required|max:191',
            'selling_price'=>'required|regex:/^\d+(\.\d{1,2})?$/',
            'original_price'=>'required|regex:/^\d+(\.\d{1,2})?$/',
            'quantity'=> 'required|numeric|min:1|max:500',
            'image'=>'required|image|mimes:jpg,jpeg,png,bmp,gif,svg,webp|max:10240',
        ]);


        if( $validator->fails())
        {
            return response()->json([
                'status'=>422,
                'errors'=> $validator->messages(),
            ]);
        }
        else
        {
            $product = new Product;
            $product->category_id = $request->input('category_id');
            $product->slug = Str::slug($request->input('slug'), '-');
            $product->name = $request->input('name');
            $product->description = $request->input('description');

            $product->meta_title = $request->input('meta_title');
            $product->meta_keyword = $request->input('meta_keyword');
            $product->meta_description = $request->input('meta_description');

            $product->brand = $request->input('brand');
            $product->selling_price = $request->input('selling_price');
            $product->original_price = $request->input('original_price');
            $product->quantity = $request->input('quantity');

            if ($request->hasFile('image'))
            {
                $file = $request->file('image');
                $extension = $file->getClientOriginalExtension();
                $filename = time() .'.'.$extension;
                $file->move('uploads/product/',$filename);
                $product->image = 'uploads/product/'.$filename;
            }

            $product->featured = $request->input('featured')  == true ? '1' : '0';
            $product->popular = $request->input('popular')  == true ? '1' : '0';
            $product->status = $request->input('status')  == true ? '1' : '0';
            $product->save();

            return response()->json([
                'status'=>200,
                'message'=> 'Product Added Successfully',
            ]);
        }
    }
    public function edit($id)
    {
        $product = Product::find($id);
        if($product)
        {
            return response() ->json([
                'status'=>200,
                'product'=>$product,
            ]);
        }
        else
        {
            return response() ->json([
                'status'=>404,
                'message'=>'No Product Found',
            ]);
        }
    }

    public function update(Request $request,$id)
    {
        if ($request->hasFile('image'))
                {
        $validator = Validator::make($request->all(),[
            'category_id'=>'required|max:191',
            'slug'=>'required|max:191',
            'name'=>'required|max:191',
            'meta_title'=>'required|max:191',
            'brand'=>'required|max:191',
            'selling_price'=>'required|regex:/^\d+(\.\d{1,2})?$/',
            'original_price'=>'required|regex:/^\d+(\.\d{1,2})?$/',
            'quantity'=> 'required|numeric|min:1|max:500',
            'image'=>'image|mimes:jpg,jpeg,png,bmp,gif,svg,webp|max:10240',
        ]);
    }
    else
    {
        $validator = Validator::make($request->all(),[
            'category_id'=>'required|max:191',
            'slug'=>'required|max:191',
            'name'=>'required|max:191',
            'meta_title'=>'required|max:191',
            'brand'=>'required|max:191',
            'selling_price'=>'required|regex:/^\d+(\.\d{1,2})?$/',
            'original_price'=>'required|regex:/^\d+(\.\d{1,2})?$/',
            'quantity'=> 'required|numeric|min:1|max:500'
        ]);
    }

        if( $validator->fails())
        {
            return response()->json([
                'status'=>422,
                'errors'=> $validator->messages(),
            ]);
        }
        else
        {
            $product = Product::find($id);
            if($product)
            {
                $product->category_id = $request->input('category_id');
                $product->slug = Str::slug($request->input('slug'), '-');
                $product->name = $request->input('name');
                $product->description = $request->input('description');

                $product->meta_title = $request->input('meta_title');
                $product->meta_keyword = $request->input('meta_keyword');
                $product->meta_description = $request->input('meta_description');

                $product->brand = $request->input('brand');
                $product->selling_price = $request->input('selling_price');
                $product->original_price = $request->input('original_price');
                $product->quantity = $request->input('quantity');

                if ($request->hasFile('image'))
                {
                    $path = $product->image;
                    if(File::exists($path));
                    {
                        File::delete($path);
                    }
                    $file = $request->file('image');
                    $extension = $file->getClientOriginalExtension();
                    $filename = time() .'.'.$extension;
                    $file->move('uploads/product/',$filename);
                    $product->image = 'uploads/product/'.$filename;
                }

                $product->featured = $request->input('featured');
                $product->popular = $request->input('popular');
                $product->status = $request->input('status');
                $product->update();

                return response()->json([
                    'status'=>200,
                    'message'=> 'Product Updates Successfully',
                ]);
            }
            else
            {
                return response()->json([
                    'status'=>404,
                    'message'=> 'Product Not Found',
                ]);
            }
        }
    }

    public function destroy($id)
    {
        $product = Product::find($id);
        if($product)
        {
            $product->delete();
            return response()->json([
                'status'=>200,
                'message'=>'Category Deleted Successfully',
            ]);
        }
        else
        {
            return response()->json([
                'status'=>400,
                'message'=>'No Category ID Found',
            ]);
        }
    }


}
