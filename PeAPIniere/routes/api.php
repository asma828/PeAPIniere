<?php

use App\Http\Controllers\OrderController;
use App\Http\Controllers\StatsController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\PlantController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:api')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
});


// admin routes
Route::middleware(['auth:api','role:admin'])->group(function () {
    Route::apiResource('categories', CategoryController::class);
    // Route::apiResource('plants', PlantController::class);
    Route::post('/plants', [PlantController::class, 'store']);
    Route::get('/plants/{slug}', [PlantController::class, 'show']);
    Route::put('/plants/{slug}', [PlantController::class, 'update']);
    Route::delete('/plants/{slug}', [PlantController::class, 'destroy']);
    Route::get('/stats', [StatsController::class, 'index']);
});
// client routes
Route::middleware(['auth:api','role:client'])->group(function () {
   Route::get('/plants', [PlantController::class, 'index']);
   Route::get('/plants/{slug}',[PlantController::class,'show']);
   Route::post('/orders', [OrderController::class, 'store']);
   Route::get('orders/{order}', [OrderController::class, 'show']);
   Route::delete('/orders/{id}', [OrderController::class, 'cancel']);
});




