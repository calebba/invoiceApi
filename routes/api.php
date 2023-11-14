<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CustomerController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::view('/swagger', 'swagger');
    // Authentication routes
Route::get('/unauthorized', function (Request $request) {
    return response()->json(['message' => 'Unauthenticated'], 401);
})->name('login.json');

//authentication routes
Route::group(['prefix'=> 'v1'], function () {
    Route::post('register', [AuthController::class, 'register'])->name('register.api');
    Route::post('login', [AuthController::class, 'login'])->name('login.api');
});

Route::group(['prefix' => 'v1'], function () {
    Route::get('/users', [UserController::class, 'index'])->middleware('superAdmin');
    Route::get('/users/{id}', [UserController::class, 'show'])->middleware('superAdmin');
    Route::patch('/users/{id}', [UserController::class, 'update'])->middleware('superAdmin');
    Route::delete('/users/{id}', [UserController::class, 'destroy'])->middleware('superAdmin');
});

Route::middleware('auth:api')->prefix('v1')->group(function () {
    //Products api routes
    Route::get('/products', [ProductController::class, 'index']);
    Route::post('/products', [ProductController::class, 'store']);
    Route::get('/products/{id}', [ProductController::class, 'show']);
    Route::patch('/products/{id}', [ProductController::class, 'update']);
    Route::delete('/products/{id}', [ProductController::class, 'destroy']);

    //customers api routes
    Route::get('/customers', [CustomerController::class, 'index']);
    Route::post('/customers', [CustomerController::class, 'store']);
    Route::get('/customers/{id}', [CustomerController::class, 'show']);
    Route::patch('/customers/{id}', [CustomerController::class, 'update']);
    Route::delete('/customers/{id}', [CustomerController::class, 'destroy']);

    //invoices api routes
    Route::get('/invoices', [InvoiceController::class, 'index']);
    Route::post('/invoices', [InvoiceController::class, 'store']);
    Route::get('/invoices/{id}', [InvoiceController::class, 'show']);
    Route::patch('/invoices/{id}', [InvoiceController::class, 'updateInvoice']);
    Route::delete('/invoices/{id}', [InvoiceController::class, 'destroy']);
    Route::delete('/invoices/item/{id}', [InvoiceController::class, 'destroyItem']);

    Route::post('logout', [AuthController::class, 'logout'])->name('logout.api');
});


