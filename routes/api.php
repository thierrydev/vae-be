<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Api\V1\CustomerController;
use App\Http\Controllers\Api\V1\InvoiceController;
use App\Http\Controllers\Api\V1\UserController;

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

// api/v1 routes without auth gate
Route::group(
    [
        'prefix' => 'v1',
        'namespace' => 'App\Http\Controllers\Api\V1',
    ],
    function () {

        Route::post('/register', [AuthController::class, 'register']);
        Route::post('/login', [AuthController::class, 'login']);
    }
);


// api/v1 routes with auth gate
Route::group(
    [
        'prefix' => 'v1',
        'namespace' => 'App\Http\Controllers\Api\V1',
        'middleware' => 'auth:sanctum',
    ],
    function () {

        Route::get('/customers', [CustomerController::class, 'index']);
        Route::put('/customers', [CustomerController::class, 'store']);
        Route::patch('/customers', [CustomerController::class, 'update']);
        Route::delete('/customers', [CustomerController::class, 'destroy']);


        Route::get('/invoices', [InvoiceController::class, 'show']);
        Route::put('/invoices', [InvoiceController::class, 'store']);
        Route::patch('/invoices', [InvoiceController::class, 'update']);
        Route::delete('/invoices', [InvoiceController::class, 'destroy']);
        Route::post('/invoices/bulk', ['uses' => 'InvoiceController@bulkCreate']);

        Route::get('/users', [UserController::class, 'index']);
        Route::patch('/users', [UserController::class, 'update']);
        Route::delete('/users', [UserController::class, 'delete']);

        Route::post('/logout', [AuthController::class, 'logout']);
    }
);
