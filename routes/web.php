<?php

// … vos routes web ici …

// fallback pour SPA, exclut /api/*

use App\Http\Controllers\Api\PaymentController;
use Illuminate\Support\Facades\Route;

Route::get('/cinetpay/success', [PaymentController::class, 'successPayment'])->name('cinetpay.success');
Route::post('/cinetpay/notify', [PaymentController::class, 'notificatePayment'])->name('cinetpay.notify');

Route::get('{any}', function () {
    return view('welcome');
})->where('any', '^(?!api).*$');
