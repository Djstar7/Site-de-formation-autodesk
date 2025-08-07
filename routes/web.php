<?php

// … vos routes web ici …

// fallback pour SPA, exclut /api/*

use App\Http\Controllers\Api\PaymentController;
use Illuminate\Support\Facades\Route;

use Illuminate\Support\Facades\Artisan;


Route::get('/cinetpay/success', [PaymentController::class, 'successPayment'])->name('cinetpay.success');
Route::post('/cinetpay/notify', [PaymentController::class, 'notificatePayment'])->name('cinetpay.notify');

Route::get('/callback', function(){
    redirect()->route('payment.callback');
})->name('payment.callback');
Route::get('/clear-config', function () {
    Artisan::call('config:cache');
    return 'Cache généré ✅';
});

Route::get('/run-migrations', function () {
    Artisan::call('migrate', ['--force' => true]);
    return 'Migrations exécutées avec succès ✅';
});

Route::get('{any}', function () {
    return view('welcome');
})->where('any', '^(?!api).*$');
