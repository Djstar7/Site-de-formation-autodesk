<?php

use App\Http\Controllers\Api\OrdersController;
use App\Http\Controllers\Api\PlansController;
use App\Http\Controllers\Api\TrainingsController;
use App\Http\Controllers\Api\UsersoneController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\PaymentController;

Route::get('users', [UsersoneController ::class, 'indexUsersone']);
Route::get('orders', [OrdersController ::class, 'indexOrders']);

Route::post('register', [UsersoneController::class, 'registerUsersone']);
Route::post('login', [UsersoneController::class, 'loginUsersone']);



Route::middleware('auth:sanctum')->group(function(){
    Route::get('trainings', [TrainingsController ::class, 'indexTrainings']);
    Route::get('trainings/{id}', [TrainingsController ::class, 'showTrainings']);

    Route::get('plans', [PlansController ::class, 'indexPlans']);
    Route::get('plans/{id}', [PlansController ::class, 'showPlans']);

    Route::get('orders/{id}', [OrdersController::class, 'showOrders']);
    Route::post('orders/store', [OrdersController::class, 'storeOrders']);
    Route::delete('orders/destroy', [OrdersController::class, 'destroyOrders']);

    Route::post('pay/intierer', [PaymentController::class, 'initiateCinetpayPayment']);
});






