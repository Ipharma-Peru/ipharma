<?php

use App\Http\Controllers\Api\VentaController;
use App\Http\Controllers\Api\ProductController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::controller(VentaController::class)->group(function() {
    Route::post('/productscode', 'getProductsByCode');
});

Route::controller(ProductController::class)->group(function() {
    Route::post('/products', 'getProductsSearch');
    Route::post('/allproducts', 'getAllProducts');
    Route::get('/productInit', 'getInitialData');
    Route::post('/subclassbyid', 'getSubclassesByIdClass');
});
