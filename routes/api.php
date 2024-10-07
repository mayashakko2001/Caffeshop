<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProductController;

//.................................................................................................
Route::get('getAllproducts',[ProductController::class,'getAllproducts']);
Route::get('getProductById/{id}', [ProductController::class, 'getProductById']);
Route::get('searchProduct/{name}',[ProductController::class,'searchProduct']);
Route::get('get_All_Catogery',[ProductController::class,'get_All_Catogery']);
Route::get('get_Catogery_By_Id/{id}',[ProductController::class,'get_Catogery_By_Id']);

Route::put('update_order/{id}',[ProductController::class,'update_order']);



Route::post('add_payment',[ProductController::class,'add_payment']);


Route::post('register_customer',[UserController::class,'register_customer']);
Route::post('register_admin',[UserController::class,'register_admin']);
Route::post('login',[UserController::class,'login']);

Route::middleware(['auth:sanctum', 'regular_user'])->group(function() {
    Route::post('add_complaint', [UserController::class, 'add_complaint']);
    Route::get('get_orderById/{id}',[ProductController::class,'get_orderById']);
    Route::post('add_order_product',[ProductController::class,'add_order_product']);
    Route::post('add_order',[ProductController::class,'add_order']);
    Route::get('get_payment_ById/{id}',[ProductController::class,'get_payment_ById']);
});

//.........................................................................................
Route::middleware(['auth:sanctum','throttle:60,1'])->group(function(){
    Route::post('add_product',[ProductController::class,'addProduct']);
    Route::delete('deleteProduct/{id}',[ProductController::class,'deleteProduct']);
    Route::put('updateProduct/{id}',[ProductController::class,'updateProduct']);
    Route::post('add_Catogery',[ProductController::class,'add_Catogery']);
    Route::delete('delete_Catogery/{id}',[ProductController::class,'delete_Catogery']);
    Route::delete('delete_order/{id}',[ProductController::class,'delete_order']);
    Route::get('get_order_product_byId/{id}',[ProductController::class,'get_order_product_byId']);
    Route::post('add_address',[ProductController::class,'add_address']);
    Route::put('update_address/{id}',[ProductController::class,'update_address']);
    Route::delete('delete_address/{id}',[ProductController::class,'delete_address']);
    Route::put('updateStateOrder/{id}',[ProductController::class,'updateStateOrder']);
   
    Route::get('get_all_complaints',[UserController::class,'get_all_complaints']);
    Route::get('get_complaint_byId/{id}',[UserController::class,'get_complaint_byId']);
    Route::get('get_complaint_byUserId/{user_id}',[UserController::class,'get_complaint_byUserId']);
    Route::get('get_all_order',[ProductController::class,'get_all_order']);
    Route::get('get_all_payments',[ProductController::class,'get_all_payments']);
    Route::get('get_all_order_product',[ProductController::class,'get_all_order_product']);
    Route::delete('delete_user/{id}',[UserController::class,'delete_user']);
    Route::put('update_user/{id}',[UserController::class,'update_user']);
    Route::get('get_user_byId/{user_id}',[UserController::class,'get_user_byId']);
    Route::get('get_all_user',[UserController::class,'get_all_user']);
    Route::get('searchUser/{name}',[UserController::class,'searchUser']);
});