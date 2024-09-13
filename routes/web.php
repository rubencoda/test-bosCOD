<?php

use App\Http\Controllers\TransferController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('login');
})->name('login');

Route::middleware(['auth'])->group(function () {
    Route::get('transfer', [TransferController::class, 'transfer'])->name('transfer');
});
