<?php

use App\Http\Controllers\Dashboard\CategoryController;
use App\Http\Controllers\Dashboard\Client\OrderController;
use App\Http\Controllers\Dashboard\ClientController;
use Spatie\FlareClient\View;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Dashboard\DashboardController;
use App\Http\Controllers\Dashboard\OrderController as DashboardOrderController;
use App\Http\Controllers\Dashboard\ProductController;
use App\Http\Controllers\Dashboard\UserController;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

Route::group(['prefix' => LaravelLocalization::setLocale(),'middleware' => [ 'localeSessionRedirect', 'localizationRedirect', 'localeViewPath' ]],
 function(){

    Route::prefix('dashboard')->name('dashboard.')->middleware(['auth'])->group(function () {

        Route::get('/',[DashboardController::class,'index'])->name('welcome');

        Route::resource('users',UserController::class)->except('show');

        Route::resource('categories',CategoryController::class)->except('show');

        Route::resource('products',ProductController::class)->except('show');

        Route::resource('clients',ClientController::class)->except('show');

        Route::resource('clients.orders',OrderController::class)->except('show');

        Route::resource('orders',DashboardOrderController::class)->except('show');

        Route::get('/orders/{order}/products',[DashboardOrderController::class,'products'])->name('orders.products');

        Route::get('export_products', [ProductController::class, 'export'])->name('export-product');




    });
});

//require __DIR__.'/auth.php';



