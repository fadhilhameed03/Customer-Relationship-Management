<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\API\CartController;
use App\Http\Controllers\API\CategoryController;
use App\Http\Controllers\API\CheckoutController;
use App\Http\Controllers\API\FrontendController;
use App\Http\Controllers\API\OrderController;
use App\Http\Controllers\API\ProductController;
use App\Http\Controllers\API\CustomerController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

Route::get('getCategory',[FrontendController::class ,'category']);
Route::get('fetchproducts/{slug}',[FrontendController::class ,'fetchproducts']);
Route::get('viewproductdetail/{category_slug}/{product_slug}',[FrontendController::class ,'viewproduct']);
Route::post('add-to-cart',[CartController::class,'addtocart']);
Route::get('cart',[CartController::class,'viewcart']);
Route::put('cart-updatequantity/{cart_id}/{scope}',[CartController::class,'updatequantity']);
Route::delete('delete-cartitem/{cart_id}',[CartController::class,'deleteCartitem']);

Route::post('validate-order',[CheckoutController::class,'validateOrder']);
Route::post('place-order',[CheckoutController::class,'placeorder']);



Route::middleware(['auth:sanctum', 'apiadmin'])->group(function () {
    Route::get('/checkauthenticated', function () {
        return response()->json(['message' => 'You are in', 'status' => 200], 200);
    });


    // Category
    Route::get('view-category',[CategoryController::class,'index']);
    Route::post('store-category',[CategoryController::class,'store']);
    Route::get('edit-category/{id}',[CategoryController::class,'edit']);
    Route::put('update-category/{id}',[CategoryController::class,'update']);
    Route::delete('delete-category/{id}',[CategoryController::class,'destroy']);
    Route::get('all-category',[CategoryController::class,'allcategory']);

    //Orders
    Route::get('orders',[OrderController::class,'index']);
    Route::get('view-order/{id}', [OrderController::class, 'vieworder']);
    Route::get('customer-orders/{id}', [OrderController::class, 'customerOrders']); // id = customer_id


    //Products
    Route::post('store-product',[ProductController::class, 'store']);
    Route::get('view-product',[ProductController::class, 'index']);
    Route::get('edit-product/{id}',[ProductController::class,'edit']);
    Route::post('update-product/{id}',[ProductController::class,'update']);
    Route::delete('delete-product/{id}',[ProductController::class,'destroy']);

    // Customers
    Route::get('customers',[CustomerController::class,'index']);
});

Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('logout', [AuthController::class, 'logout']);
});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
