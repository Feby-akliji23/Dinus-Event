<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\EventController;
use App\Http\Controllers\Admin\TiketController;
use App\Http\Controllers\Admin\HistoriesController;
use App\Http\Controllers\Admin\PaymentTypeController;
use App\Http\Controllers\Admin\PaymentStatusController;
use App\Http\Controllers\Admin\PromoController;
use App\Http\Controllers\Admin\TicketTypeController;
use App\Http\Controllers\Admin\EventController as AdminEventController;
use App\Http\Controllers\User\EventController as UserEventController;
use App\Http\Controllers\User\HomeController;
use App\Http\Controllers\User\OrderController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');

// Events
Route::get('/events/{event}', [UserEventController::class, 'show'])->name('events.show');

Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
Route::post('/orders', [OrderController::class, 'store'])->name('orders.store');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::middleware('admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

        // Category Management
        Route::resource('categories', CategoryController::class);

        // Ticket Type Management
        Route::resource('ticket-types', TicketTypeController::class);

        // Payment Type Management
        Route::resource('payment-types', PaymentTypeController::class);
        // Payment Status Management
        Route::resource('payment-statuses', PaymentStatusController::class);
        // Promo Management
        Route::resource('promos', PromoController::class);

        // Event Management
        Route::resource('events', EventController::class);

        // Tiket Management 
        Route::resource('tickets', TiketController::class);
        
        // Histories
        Route::get('/histories', [HistoriesController::class, 'index'])->name('histories.index');
        Route::get('/histories/{id}', [HistoriesController::class, 'show'])->name('histories.show');
        Route::patch('/histories/{order}/payment-status', [HistoriesController::class, 'updatePaymentStatus'])->name('histories.payment-status.update');

    });
});

require __DIR__.'/auth.php';
