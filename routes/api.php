<?php

use App\Http\Controllers\Auth\RegistrationController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ReviewController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;


# this is used to return user related to the token included in the header
Route::group(['middleware' => ['auth:sanctum']], function ($route) {

    # to authenticate user by token
    $route->get('/user', [LoginController::class, 'user']);

    # logout functionality
    $route->post('/logout', [LogoutController::class, 'logout']);


    # RestFull API for product
    Route::prefix('products')->group(function () {
        Route::get('/', [ProductController::class, 'index']);
        Route::get('/{product}', [ProductController::class, 'show']);
        Route::post('/', [ProductController::class, 'store']);
        Route::put('/{product}', [ProductController::class, 'update']);
        Route::delete('/{product}', [ProductController::class, 'destroy']);
    });

    # RestFull API for category
    Route::prefix('categories')->group(function () {
        Route::get('/', [CategoryController::class, 'index']);
        Route::get('/{category}', [CategoryController::class, 'show']);
        Route::post('/', [CategoryController::class, 'store']);
        Route::put('/{category}', [CategoryController::class, 'update']);
        Route::delete('/{category}', [CategoryController::class, 'destroy']);
    });

    # RestFull API for review
    Route::prefix('reviews')->group(function () {
        Route::get('/', [ReviewController::class, 'index']);
        Route::get('/{review}', [ReviewController::class, 'show']);
        Route::post('/', [ReviewController::class, 'store']);
        Route::put('/{review}', [ReviewController::class, 'update']);
        Route::delete('/{review}', [ReviewController::class, 'destroy']);
    });

});

# an Endpoint to login to the system
Route::post('/login', [LoginController::class, 'login']);

# an Endpoint to register in the system
Route::post('/register', [RegistrationController::class, 'register']);

