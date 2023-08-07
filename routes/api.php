<?php

use App\Http\Controllers\API\CartAPIController;
use App\Http\Controllers\API\ProductsAPIController;
use App\Http\Controllers\API\UserLoginAPIController;
use App\Http\Controllers\API\UserAPIController;
use Illuminate\Http\Request;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Auth
Route::prefix('user')->group(function () {
    Route::post('/register', [UserLoginAPIController::class, 'register']);
    Route::post('/login', [UserLoginAPIController::class, 'login']);
    Route::get('/logout/{id}', [UserLoginAPIController::class, 'logout']);
});

// Merchant
Route::group(['middleware' => ['APIToken', 'IsMerchant']], function () {
    Route::prefix('product')->group(function () {
        Route::post('/create', [ProductsAPIController::class, 'store']);
        Route::post('/update/{id}', [ProductsAPIController::class, 'update']);
        Route::delete('/delete/{id}', [ProductsAPIController::class, 'destroy']);
    });
});

Route::prefix('product')->group(function () {
    Route::get('/', [ProductsAPIController::class, 'index']);
    Route::get('/show/{id}', [ProductsAPIController::class, 'show']);
});

// UserCarts
Route::group(['middleware' => ['APIToken', 'IsCustomer']], function () {
    Route::prefix('userCart')->group(function () {
        Route::post('/add', [CartAPIController::class, 'store']);
        Route::get('/show', [CartAPIController::class, 'show']);
    });
});


// basic users
Route::group(['middleware' => ['APIToken', 'IsMerchant']], function () {
    Route::prefix('user')->group(function () {
        Route::get('/', [UserAPIController::class, 'index']);
        Route::get('/show/{id}', [UserAPIController::class, 'show']);
        Route::post('/update/{id}', [UserAPIController::class, 'update']);
        Route::delete('/delete/{id}', [UserAPIController::class, 'destroy']);
    });
});


