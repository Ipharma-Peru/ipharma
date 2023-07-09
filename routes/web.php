<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::group(['middleware' => ['auth:sanctum', 'verified']], function() {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
    Route::get('/blogs', function () {
        return view('blogs');
    })->name('blogs');
    // Route::group(['prefix' => 'components', 'as' => 'components.'], function() {
    //     Route::get('/alert', function () {
    //         return view('admin.component.alert');
    //     })->name('alert');
    //     Route::get('/accordion', function () {
    //         return view('admin.component.accordion');
    //     })->name('accordion');
    // });
    Route::group(['prefix' => 'caja', 'as' => 'caja.'], function() {
        Route::get('/nota-credito', function () {
            return view('admin.caja.nota');
        })->name('nota');
        Route::get('/ventas', function () {
            return view('admin.caja.ventas');
        })->name('ventas');
        Route::get('/list', function () {
            return view('admin.caja.list');
        })->name('list');
    });
    Route::group(['prefix' => 'inventario', 'as' => 'inventario.'], function() {
        Route::get('/products', function () {
            return view('admin.inventory.products');
        })->name('products');
         Route::get('products/{any}', function () {
            return view('admin.inventory.products');
        })->where('any', '.*');
        Route::get('/shopping', function () {
            return view('admin.inventory.shopping');
        })->name('shopping');
        Route::get('shopping/{any}', function () {
            return view('admin.inventory.shopping');
        })->where('any', '.*');
       
       
    });
    // Route::get('/{any}', function () {
    //     return view('blogs');
    // })->where('any', '.*');
});