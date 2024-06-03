<?php

use Illuminate\Support\Facades\Route;
use App\Http\Middleware\ApiAuthMiddleware;
use App\Http\Controllers\API\AdminController;
use App\Http\Controllers\API\AssetController;
use App\Http\Controllers\API\LoginAdminController;
use App\Http\Controllers\API\RegisterController;
use App\Http\Controllers\BorrowingDateController;
use Illuminate\Auth\Events\Registered;

Route::prefix('v1')->group(function () {

    // Admin Routes
    Route::group(['prefix' => 'admins', 'as' => 'admin.'], function () {
        Route::post('register', RegisterController::class)->name('register');
        Route::post('login', LoginAdminController::class)->name('login');

        Route::middleware(ApiAuthMiddleware::class)->group(function () {
            Route::get('current', [AdminController::class, 'show'])->name('current');
            Route::delete('logout', [AdminController::class, 'logout'])->name('logout');
        });
        Route::get('/user', function () {
            return auth()->guard('admin')->user();
        });
    });

    // Asset Routes
    Route::group(['prefix' => 'assets', 'as' => 'asset.', 'middleware' => ApiAuthMiddleware::class], function () {
        Route::get('/', [AssetController::class, 'index'])->name('index');
        Route::post('/', [AssetController::class, 'store'])->name('store');
        Route::get('{id}', [AssetController::class, 'show'])->where('id', '[0-9]+')->name('show');
        Route::put('{id}', [AssetController::class, 'update'])->where('id', '[0-9]+')->name('update');
        Route::delete('{id}', [AssetController::class, 'destroy'])->where('id', '[0-9]+')->name('delete');

        // Borrowing Date
        Route::prefix('{id}')->name('borrowingDate.')->group(function () {
            Route::get('borrowing-date', [BorrowingDateController::class, 'index'])->name('index');
        });
    });
});