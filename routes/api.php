<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\ApiAuthMiddleware;
use App\Http\Controllers\API\AdminController;
use App\Http\Controllers\API\AssetController;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

Route::get('/admins', function () {
    return "testing";
});
Route::post('/admins', [AdminController::class, 'register']);
Route::post('/admins/login', [AdminController::class, 'login']);

Route::middleware(ApiAuthMiddleware::class)->group(function () {
    Route::get('/admins/current', [AdminController::class, 'show']);

    Route::get('/assets', [AssetController::class, 'index']);
    Route::post('/assets', [AssetController::class, 'store']);
    Route::get('/assets/{id}', [AssetController::class, 'show'])->where('id', '[0-9]+');
    Route::put('/assets/{id}', [AssetController::class, 'update'])->where('id', '[0-9]+');
    Route::delete('/assets/{id}', [AssetController::class, 'destroy'])->where('id', '[0-9]+');
});
