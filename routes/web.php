<?php

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\IndexController;
use App\Http\Controllers\LogoutController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\WarrantyClaim\WarrantyClaimController;


Route::group(['middleware' => ['auth']], function () {

    Route::get('/', function () {
        return redirect()->route('app.home.index');
    });

    Route::get('/home', [HomeController::class, 'index'])->name('app.home.index');
    Route::get('/warranty_claims', [WarrantyClaimController::class, 'index'])->name('app.warranty.index');
    Route::get('/search', [WarrantyClaimController::class, 'search'])->name('app.search');

});

Route::group(['middleware' => ['guest']], function () {
     Route::get('/login', [LoginController::class, 'index'])->name('index');
     Route::post('login', [LoginController::class, 'login'])->name('login');
    });

    Route::post('logout', [LogoutController::class, 'logout'])->name('logout');
