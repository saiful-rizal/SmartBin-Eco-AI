<?php

use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
Route::post('/add-trash', [DashboardController::class, 'addTrash'])->name('add.trash');
Route::get('/reset', [DashboardController::class, 'reset'])->name('reset');