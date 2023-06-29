<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\PresentationController;
use App\Http\Controllers\Api\VentaController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\ActiveSubstanceController;
use App\Http\Controllers\Api\PharmaActionController;
use App\Http\Controllers\Api\LaboratoryController;
use App\Http\Controllers\Api\ProviderController;

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
    Route::post('/registerproduct', 'store');
});

Route::controller(PresentationController::class)->group(function() {
    Route::post('/addpresentation', 'store');
});

Route::controller(LaboratoryController::class)->group(function() {
    Route::post('/addlaboratory', 'store');
});

Route::controller(PharmaActionController::class)->group(function() {
    Route::post('/addpharmaaction', 'store');
});

Route::controller(ActiveSubstanceController::class)->group(function() {
    Route::post('/addsubstanceactive', 'store');
});

Route::controller(ProviderController::class)->group(function() {
    Route::post('/addprovider', 'store');
});
