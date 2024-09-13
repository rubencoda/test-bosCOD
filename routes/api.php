<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\TransferController;
use Illuminate\Support\Facades\Route;


Route::post('auth/login', [AuthController::class, 'login'])->name('doLogin');

Route::group(['middleware' => 'jwt.verify', 'prefix' => 'auth'], function ($router) {
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('refresh', [AuthController::class, 'refresh'])->name('refreshToken');
    Route::post('me', [AuthController::class, 'me']);
});

Route::post('create_transfer', [TransferController::class, 'create'])->middleware('jwt.verify')->name('create_transfer');
