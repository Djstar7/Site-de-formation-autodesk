<?php

use App\Http\Controllers\Api\OrdersController;
use App\Http\Controllers\Api\PlansController;
use App\Http\Controllers\Api\TrainingsController;
use App\Http\Controllers\Api\UsersoneController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\PaymentController;
use App\Http\Controllers\Api\DashboardController;

Route::get('/dashboard', [DashboardController::class, 'indexDashboard']);

Route::get('orders', [OrdersController::class, 'indexOrders']);

Route::post('register', [UsersoneController::class, 'registerUsersone']);
Route::post('login', [UsersoneController::class, 'loginUsersone']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('trainings', [TrainingsController::class, 'indexTrainings']);
    Route::get('trainings/{id}', [TrainingsController::class, 'showTrainings']);

    Route::get('plans', [PlansController::class, 'indexPlans']);
    Route::get('plans/{id}', [PlansController::class, 'showPlans']);

    Route::get('orders/{id}', [OrdersController::class, 'showOrders']);
    Route::post('orders/store', [OrdersController::class, 'storeOrders']);
    Route::delete('orders/destroy', [OrdersController::class, 'destroyOrders']);

    // Route::post('pay/initier', [PaymentController::class, 'initiateCinetpayPayment']);
    Route::post('pay/initier', [PaymentController::class, 'createPayment']);
    
    Route::prefix('admin')->group(function () {
        // Dashboard

        // Users
        Route::get('/users', [UsersoneController::class, 'indexUsersone']);
        Route::post('/users/store', [UsersoneController::class, 'storeUsersone']);
        Route::put('/users/update/{id}', [UsersoneController::class, 'updateUsersone']);
        Route::delete('/users/destroy/{id}', [UsersoneController::class, 'destroyUsersone']); // 🔧 correction ici

        // Trainings
        Route::post('/trainings/store', [TrainingsController::class, 'storeTrainings']);
        Route::post('/trainings/update{id}', [TrainingsController::class, 'updateTrainings']);
        Route::delete('/trainings/destroy/{id}', [TrainingsController::class, 'destroyTrainings']);

        // Plans
        Route::post('/plans/store', [PlansController::class, 'storePlans']);
        Route::post('/plans/update/{id}', [PlansController::class, 'updatePlans']);
        Route::delete('/plans/destroy/{id}', [PlansController::class, 'destroyPlans']);
    });

});