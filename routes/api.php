<?php

use Illuminate\Support\Facades\Route;
use App\Http\Middleware\ApiAuthMiddleware;
use App\Http\Controllers\API\AdminController;
use App\Http\Controllers\API\AssetController;
use App\Http\Controllers\API\BorrowingController;
use App\Http\Controllers\API\LoginAdminController;
use App\Http\Controllers\API\RegisterController;
use App\Http\Controllers\BorrowingDateController;
use App\Http\Middleware\CheckRole;
use Illuminate\Auth\Events\Registered;

Route::prefix('v1')->group(function () {

    // Admin Routes
    Route::group(['prefix' => 'admins', 'as' => 'admin.'], function () {
        Route::post('register', RegisterController::class)->name('register');
        Route::post('login', LoginAdminController::class)->name('login');

        Route::middleware(['auth'])->group(function () {
            Route::get('users', [AdminController::class, 'index'])->name('users');
            Route::put('users/{id}', [AdminController::class, 'update'])->name('users.update');

        });

        Route::middleware(['auth', 'role:logistik,ssc,superuser'])->group(function () {
            Route::get('current', [AdminController::class, 'show'])->name('current');
            Route::delete('logout', [AdminController::class, 'logout'])->name('logout');
        });
        Route::middleware(['auth'])->group(function () {
            Route::get('/user', function () {
                return auth()->guard('student')->user()->type;
            });
        });
    });

    // Student Routes
    Route::group(['prefix' => 'students', 'as' => 'student.'], function () {
        Route::post('login', LoginAdminController::class)->name('login');

        Route::middleware(['auth', 'role:student'])->group(function () {
            Route::get('current', [AdminController::class, 'show'])->name('current');
            Route::delete('logout', [AdminController::class, 'logout'])->name('logout');
        });
        Route::middleware(['auth'])->group(function () {
            Route::get('/user', function () {
                return auth()->guard('student')->user()->type;
            });
        });
    });

    // Asset Routes
    Route::group(['prefix' => 'assets', 'as' => 'asset.', 'middleware' => ['auth', 'role:logistik,ssc,student']], function () {
        Route::get('/', [AssetController::class, 'index'])->name('index');
        ;
        Route::post('/', [AssetController::class, 'store'])->middleware('role:logistik')->name('store');
        Route::get('{id}', [AssetController::class, 'show'])->where('id', '[0-9]+')->name('show');
        Route::put('{id}', [AssetController::class, 'update'])->where('id', '[0-9]+')->middleware('role:logistik')->name('update');
        Route::delete('{id}', [AssetController::class, 'destroy'])->where('id', '[0-9]+')->middleware('role:logistik')->name('delete');
    });

    // Borrowings Routes
    Route::group(['prefix' => 'borrowings', 'as' => 'borrowings.', 'middleware' => ['auth', 'role:logistik,ssc,student']], function () {
        Route::get('/', [BorrowingController::class, 'index'])->name('index');
        Route::post('/', [BorrowingController::class, 'store'])->name('store');
        Route::get('/{id}', [BorrowingController::class, 'show'])->where('id', '[0-9]+')->name('show');
        Route::put('/{id}', [BorrowingController::class, 'update'])->where('id', '[0-9]+')->middleware('role:logistik')->name('update');
        Route::delete('/{id}', [BorrowingController::class, 'destroy'])->where('id', '[0-9]+')->middleware('role:logistik')->name('destroy');
        Route::patch('/{id}/approval', [BorrowingController::class, 'approve'])->where('id', '[0-9]+')->middleware('role:logistik')->name('approve');
    });
});
