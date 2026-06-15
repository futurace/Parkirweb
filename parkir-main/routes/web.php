<?php

use App\Http\Controllers\LocationController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\VehicleTypeController;
use Illuminate\Support\Facades\Route;

Route::get('/', [TransactionController::class, 'index'])->name('dashboard');
Route::redirect('/dashboard', '/');
Route::get('/transactions/report', [TransactionController::class, 'report'])->name('transactions.report');
Route::get('/locations/report', [LocationController::class, 'report'])->name('locations.report');
Route::resource('locations', LocationController::class)->except('show');
Route::resource('vehicle-types', VehicleTypeController::class)->parameters([
    'vehicle-types' => 'vehicleType',
])->except('show');
Route::post('/transactions/exit', [TransactionController::class, 'exit'])->name('transactions.exit');
Route::resource('transactions', TransactionController::class)->except('show');
