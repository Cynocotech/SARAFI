<?php

use App\Http\Controllers\Api\OfficeRatesController;
use App\Http\Controllers\Api\SpecialRateController;
use App\Http\Controllers\Api\PaymentMethodsController;
use App\Http\Controllers\Api\TransferFeeController;
use App\Http\Controllers\Api\DashboardStatsController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes — Exchange Dashboard
|--------------------------------------------------------------------------
| All routes require the exchange guard (session-based, same as web).
| Rate limiting: 60 requests/min per IP via the "api" throttle.
|--------------------------------------------------------------------------
*/

Route::middleware(['web', 'auth.exchange', 'throttle:60,1'])->group(function () {

    // Dashboard stats
    Route::get('/dashboard/stats', [DashboardStatsController::class, 'show']);

    // Exchange rates
    Route::get('/offices/{office}/rates',   [OfficeRatesController::class, 'index']);
    Route::post('/offices/{office}/rates',  [OfficeRatesController::class, 'store']);
    Route::put('/rates/{rate}',             [OfficeRatesController::class, 'update']);
    Route::delete('/rates/{rate}',          [OfficeRatesController::class, 'destroy']);

    // Special rate
    Route::put('/offices/{office}/special-rate',    [SpecialRateController::class, 'update']);
    Route::delete('/offices/{office}/special-rate', [SpecialRateController::class, 'destroy']);

    // Payment methods
    Route::put('/offices/{office}/payment-methods', [PaymentMethodsController::class, 'update']);

    // Transfer fee
    Route::put('/offices/{office}/transfer-fee', [TransferFeeController::class, 'update']);
});
