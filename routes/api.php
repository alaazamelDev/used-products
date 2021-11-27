<?php


use App\Http\Controllers\Auth\RegistrationController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;


# this is used to return user related to the token included in the header
Route::group(['middleware' => ['auth:sanctum']], function ($route) {

    # to authenticate user by token
    $route->get('/use', [LoginController::class, 'user']);

    # logout functionality
    $route->post('/logout', [LogoutController::class, 'logout']);
});


Route::post('/login', [LoginController::class, 'login']);
Route::post('/register', [RegistrationController::class, 'register']);
