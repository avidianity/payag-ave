<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CategoryProductController;
use App\Http\Controllers\FileController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SelfOrderController;
use App\Http\Controllers\UserController;
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

Route::group(['prefix' => 'auth', 'as' => 'auth.'], function () {
    Route::post('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/register', [AuthController::class, 'register'])->name('register');
    Route::post('/forgot-password', [AuthController::class, 'sendForgotPasswordEmail'])->name('password.email');
    Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.update');
});

Route::group(['middleware' => 'auth:sanctum'], function () {
    /**
     * Administrator only
     */
    Route::group(['middleware' => 'admin'], function () {
        Route::apiResource('users', UserController::class);
        Route::apiResource('files', FileController::class)->only('destroy');
    });

    /**
     * Staff only
     */
    Route::group(['middleware' => 'non-customer'], function () {
        Route::apiResource('categories', CategoryController::class)->except('index', 'show');
        Route::apiResource('products', ProductController::class)->except('index', 'show');
        Route::apiResource('orders', OrderController::class);
    });

    /**
     * Self only
     */
    Route::group(['prefix' => 'self', 'as' => 'self.'], function () {
        Route::apiResource('orders', SelfOrderController::class);
    });

    /**
     * Accessible to anyone as long as logged in
     */
    Route::apiResource('files', FileController::class)->only('index', 'show');
    Route::apiResource('categories', CategoryController::class)->only('index', 'show');
    Route::apiResource('categories.products', CategoryProductController::class)->only('index', 'show');
    Route::apiResource('products', ProductController::class)->only('index', 'show');
});
