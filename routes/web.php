<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AuthController;

/*
|--------------------------------------------------------------------------
| AUTH
|--------------------------------------------------------------------------
*/

Route::post('/login', [AuthController::class,'login'])
    ->name('login.process');

Route::post('/logout', [AuthController::class,'logout'])
    ->name('logout');


/*
|--------------------------------------------------------------------------
| PUBLIC DASHBOARD
|--------------------------------------------------------------------------
*/

Route::get('/dashboard/kinerja', [DashboardController::class, 'kinerja'])
    ->name('dashboard.kinerja');

Route::get('/dashboard/analisis', [DashboardController::class, 'analisis'])
    ->name('dashboard.analisis');

Route::redirect('/', '/dashboard/kinerja');


/*
|--------------------------------------------------------------------------
| ADMIN ONLY (LOGIN REQUIRED)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth','admin'])->group(function () {

    Route::get('/order', [DashboardController::class, 'indexOrder'])
        ->name('order.index');

    Route::get('/order/create', [DashboardController::class, 'create'])
        ->name('order.create');

    Route::post('/order/store', [DashboardController::class, 'store'])
        ->name('order.store');

    Route::get('/order/{id}', [DashboardController::class, 'showOrder'])
        ->name('order.show');

    Route::get('/order/{id}/edit', [DashboardController::class, 'editOrder'])
        ->name('order.edit');

    Route::put('/order/{id}', [DashboardController::class, 'updateOrder'])
        ->name('order.update');

    Route::delete('/order/{id}', [DashboardController::class, 'destroyOrder'])
        ->name('order.destroy');
});
