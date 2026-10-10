<?php

use App\Http\Controllers\ServerOptimizationController;
use Illuminate\Support\Facades\Route;

Route::get('/', [ServerOptimizationController::class, 'index'])->name('optimization.index');
Route::post('/reset', [ServerOptimizationController::class, 'reset'])->name('optimization.reset');
Route::put('/servers/{server}', [ServerOptimizationController::class, 'updateServer'])->name('servers.update');
Route::put('/services/{service}', [ServerOptimizationController::class, 'updateService'])->name('services.update');
