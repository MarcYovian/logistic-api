<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AdminController;
use App\Http\Controllers\API\AssetController;
use App\Http\Controllers\API\BorrowingController;
use App\Http\Controllers\API\LoginAdminController;
use App\Http\Controllers\API\RegisterController;
use App\Http\Controllers\API\StudentController;

Route::prefix('v1')->group(function () {

    // Admin Routes
    Route::group(['prefix' => 'admins', 'as' => 'admin.'], function () {
        Route::post('register', RegisterController::class)->name('register');
        Route::post('login', LoginAdminController::class)->name('login');

        Route::middleware(['auth'])->group(function () {
            Route::get('users', [AdminController::class, 'index'])->name('users');
            Route::put('users/{id}', [AdminController::class, 'update'])->name('users.update');
            Route::put('users/{id}/is-active', [AdminController::class, 'updateIsActive'])->middleware('role:superuser')->name('users.updateIsActive');
            Route::delete('users/{id}', [AdminController::class, 'destroy'])->middleware('role:superuser')->name('users.destroy');
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
    Route::get('students/current', [StudentController::class, 'current'])->middleware('auth')->name('current');

    Route::group(['prefix' => 'students', 'as' => 'student.'], function () {
        Route::post('login', [StudentController::class, 'login'])->name('login');

        Route::get('/', [StudentController::class, 'index'])->name('index');
        Route::get('/{id}', [StudentController::class, 'show'])->name('show');
        Route::middleware(['auth', 'role:student'])->group(function () {
            Route::delete('logout', [StudentController::class, 'logout'])->name('logout');
        });
        Route::middleware(['auth'])->group(function () {
            Route::get('/user', function () {
                return auth()->guard('student')->user()->type;
            });
        });
    });

    // Asset Routes
    Route::group(['prefix' => 'assets', 'as' => 'asset.', 'middleware' => ['auth', 'role:logistik,ssc,student,superuser']], function () {
        Route::get('/', [AssetController::class, 'index'])->name('index');
        Route::post('/', [AssetController::class, 'store'])->middleware('role:logistik,superuser')->name('store');
        Route::get('{id}', [AssetController::class, 'show'])->where('id', '[0-9]+')->name('show');
        Route::put('{id}', [AssetController::class, 'update'])->where('id', '[0-9]+')->middleware('role:logistik,superuser')->name('update');
        Route::delete('{id}', [AssetController::class, 'destroy'])->where('id', '[0-9]+')->middleware('role:logistik,superuser')->name('delete');
        Route::get('all-asset', [AssetController::class, 'allAsset'])->name('allAsset');
    });

    // Borrowings Routes
    Route::group(['prefix' => 'borrowings', 'as' => 'borrowings.', 'middleware' => ['auth', 'role:logistik,ssc,student,superuser']], function () {
        Route::get('/', [BorrowingController::class, 'index'])->name('index');
        Route::post('/', [BorrowingController::class, 'store'])->name('store');
        Route::get('/{id}', [BorrowingController::class, 'show'])->where('id', '[0-9]+')->name('show');
        Route::put('/{id}', [BorrowingController::class, 'update'])->where('id', '[0-9]+')->middleware('role:logistik,superuser')->name('update');
        Route::delete('/{id}', [BorrowingController::class, 'destroy'])->where('id', '[0-9]+')->middleware('role:logistik,superuser')->name('destroy');
        Route::put('/{id}/approve', [BorrowingController::class, 'approve'])->where('id', '[0-9]+')->middleware('role:logistik,superuser')->name('approve');
    });
});
