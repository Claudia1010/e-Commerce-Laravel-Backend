<?php

use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
// no token required
Route::get('/', function () {return ['Bienvenido a mi api'];});
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);
Route::get('/products', [ProductController::class, 'getProducts']);
Route::get('/categories', [CategoryController::class, 'getCategories']);
Route::get('/getProductById/{id}', [ProductController::class, 'getProductById']);
Route::get('/searchProduct/{query}', [ProductController::class, 'searchProduct']);


//routes for users with token
Route::group(["middleware" => "jwt.auth"] , function() {
    Route::post('/logout', [AuthController::class, 'logout']); 
    Route::get('/getProfile', [AuthController::class, 'getProfile']); 
    Route::put('/updateProfile', [AuthController::class, 'updateProfile']);
    Route::delete('/deleteProfile', [AuthController::class, 'deleteProfile']); 
});

Route::group(["middleware" => "jwt.auth"] , function() {
    Route::get('/getOrders', [OrderController::class, 'getOrders']);
    Route::post('/addOrder', [OrderController::class, 'addOrder']);
    Route::delete('/deleteOrderById/{id}', [OrderController::class, 'deleteOrderById']);
}); 

//routes with admin token
Route::group(["middleware" => ["jwt.auth", "isAdmin"]] , function() {
    Route::post('/promoteToAdmin/{id}', [UserController::class, 'userToAdmin']);
    Route::post('/degradeToUser/{id}', [UserController::class, 'adminToUser']);
    Route::get('/getAllUsers', [UserController::class, 'getAllUsers']);
    //get order by status
}); 

Route::group(["middleware" => ["jwt.auth",  "isAdmin"]] , function() {
    Route::get('/getAllOrders', [OrderController::class, 'getAllOrders']);
    Route::put('/changeOrderStatus/{id}', [OrderController::class, 'changeOrderStatus']);
    
}); 

Route::group(["middleware" => ["jwt.auth", "isAdmin"]] , function() {
    Route::post('/addCategory', [CategoryController::class, 'addCategory']);
    Route::put('/updateCategoryById/{id}', [CategoryController::class, 'updateCategoryById']);
    Route::delete('/deleteCategoryById/{id}', [CategoryController::class, 'deleteCategoryById']);
}); 

Route::group(["middleware" => ["jwt.auth", "isAdmin"]] , function() {
    Route::post('/addProduct', [ProductController::class, 'addProduct']);
    Route::put('/updateProductById/{id}', [ProductController::class, 'updateProductById']);
    Route::delete('/deleteProductById/{id}', [ProductController::class, 'deleteProductById']);  
});  

