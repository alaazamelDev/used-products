<?php

use App\Http\Controllers\Auth\RegistrationController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\UploadController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;


// These routes must pass authentication middleware before accessing the controllers
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
        Route::put('/{product}/views', [ProductController::class, 'increaseViews']);

        # Implement Like functionality
        Route::get('/{product}/like', [ProductController::class, 'isLikedByMe']);   // isLiked by me, boolean response
        Route::post('/{product}/like', [ProductController::class, 'like']); // add like
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

// image upload route
Route::post('/image', [UploadController::class, 'upload']);

# RestFull API for category
Route::prefix('categories')->group(function () {
    Route::get('/', [CategoryController::class, 'index']);
    Route::get('/{category}', [CategoryController::class, 'show']);
    Route::post('/', [CategoryController::class, 'store']);
    Route::put('/{category}', [CategoryController::class, 'update']);
    Route::delete('/{category}', [CategoryController::class, 'destroy']);
});


# an Endpoint to login to the system
Route::post('/login', [LoginController::class, 'login']);

# an Endpoint to register in the system
Route::post('/register', [RegistrationController::class, 'register']);

