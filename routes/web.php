<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DapurController;
use App\Http\Controllers\GudangController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware(['auth.session', 'role:gudang'])->group(function () {
    //Dashboard
    Route::get('/gudang/dashboard', [GudangController::class, 'index'])->name('gudang.index');
});

Route::middleware(['auth.session', 'role:dapur'])->group(function () {
    //Dashboard
    Route::get('/dapur/dashboard', [DapurController::class, 'index'])->name('dapur.index');
});