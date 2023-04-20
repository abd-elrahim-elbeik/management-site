<?php

use App\Http\Controllers\Dashboard\CategoryController;
use Spatie\FlareClient\View;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Dashboard\DashboardController;
use App\Http\Controllers\Dashboard\ProductController;
use App\Http\Controllers\Dashboard\UserController;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

Route::group(['prefix' => LaravelLocalization::setLocale(),'middleware' => [ 'localeSessionRedirect', 'localizationRedirect', 'localeViewPath' ]],
 function(){

    Route::prefix('dashboard')->name('dashboard.')->middleware(['auth'])->group(function () {

        Route::get('/index',[DashboardController::class,'index'])->name('index');

        Route::resource('users',UserController::class)->except('show');

        Route::resource('categories',CategoryController::class)->except('show');

        Route::resource('products',ProductController::class)->except('show');



    });
});

//require __DIR__.'/auth.php';



