<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\PredictionController;

/* |-------------------------------------------------------------------------- | Web Routes |-------------------------------------------------------------------------- | */

Route::get('/', function () {
    return redirect('/login');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class , 'index'])->name('home');

Route::middleware('auth')->group(function () {
    Route::resource('accounts', AccountController::class);
    Route::post('accounts/{account}/transactions', [TransactionController::class , 'store'])->name('transactions.store');
    Route::get('transactions/{transaction}/edit', [TransactionController::class , 'edit'])->name('transactions.edit');
    Route::put('transactions/{transaction}', [TransactionController::class , 'update'])->name('transactions.update');
    Route::delete('transactions/{transaction}', [TransactionController::class , 'destroy'])->name('transactions.destroy');
    Route::get('predictions', [PredictionController::class , 'index'])->name('predictions.index');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class , 'index'])->name('home');
