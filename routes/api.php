<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\PresentationController;
use App\Http\Controllers\Api\VentaController;
use App\Http\Controllers\Api\SaleController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\ActiveSubstanceController;
use App\Http\Controllers\Api\PharmaActionController;
use App\Http\Controllers\Api\LaboratoryController;
use App\Http\Controllers\Api\ProviderController;
use App\Http\Controllers\Api\PurchaseController;
use App\Http\Controllers\Api\ClientController;
use App\Http\Controllers\Api\InvoiceSeriesController;
use App\Http\Controllers\Api\NotaCreditoController;

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

// TODO: Eliminar esto
Route::controller(VentaController::class)->group(function() {
    Route::post('/productscode', 'getProductsByCode');
});

Route::controller(SaleController::class)->group(function() {
    Route::post('/ventas/registrar', 'registrarVenta');
    Route::post('/ventas/obtenerbyid', 'getDatosVentaById');
    Route::post('/ventas/obtenerbyserie', 'getDatosVentaBySerie');
    Route::post('/ventas/listar', 'listarVentas');
    // Route::post('/ventas/enviarDocumento', 'enviarDocumento');
    Route::post('/ventas/listaresumen', 'listarVentasResumen');
    Route::post('/ventas/reporteventas', 'listaVentasReportes');
});

Route::controller(InvoiceSeriesController::class)->group(function() {
    Route::get('/correlativo/consultar', 'getCorrelativeBySerie');
});

Route::controller(ProductController::class)->group(function() {
    Route::post('/products', 'getProductsSearch');
    Route::post('/allproducts', 'getAllProducts');
    Route::get('/productInit', 'getInitialData');
    Route::post('/subclassbyid', 'getSubclassesByIdClass');
    Route::post('/registerproduct', 'store');
    Route::post('/compras/buscarproductos', 'getProductsForSale');
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
    Route::post('/proveedor/buscar', 'searchProvider');
});

Route::controller(PurchaseController::class)->group(function() {
    Route::post('/compras/registrar', 'store');
});

Route::controller(ClientController::class)->group(function() {
    Route::post('/clientes/registrar', 'store');
    Route::post('/clientes/buscar', 'search');
});

Route::controller(InvoiceSeriesController::class)->group(function() {
    Route::post('/documentos/correlativo', 'getCorrelativeBySerieFront');
});

Route::controller(NotaCreditoController::class)->group(function() {
    Route::post('/notacredito/emitir', 'emitirNotaCredito');
});
