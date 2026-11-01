<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\OrderManagerController;
use App\Http\Controllers\Admin\ProductManagerController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SynbarraController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| SYNBARRA TECHNOLOGIES — Corporate Website Routes
| Domain: synbarra.com
|--------------------------------------------------------------------------
*/

Route::get('/', [SynbarraController::class, 'home'])->name('home');
Route::get('/about', [SynbarraController::class, 'about'])->name('about');
Route::get('/services', [SynbarraController::class, 'services'])->name('services');
Route::get('/approach', [SynbarraController::class, 'approach'])->name('approach');
Route::get('/contact', [SynbarraController::class, 'contact'])->name('contact');
Route::post('/contact', [SynbarraController::class, 'submitContact'])->name('contact.submit');

/*
|--------------------------------------------------------------------------
| Admin & Management Portal Routes
|--------------------------------------------------------------------------
*/

// Redirect /admin & /dashboard based on auth state
Route::get('/admin', function () {
    return auth()->check() ? redirect()->route('admin.dashboard') : redirect()->route('login');
})->name('admin');

Route::get('/dashboard', function () {
    return redirect()->route('admin.dashboard');
})->middleware(['auth'])->name('dashboard');

Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Products / Service Catalog Management
    Route::resource('products', ProductManagerController::class);

    // Orders / Inquiries Management
    Route::get('/orders', [OrderManagerController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [OrderManagerController::class, 'show'])->name('orders.show');
    Route::patch('/orders/{order}/status', [OrderManagerController::class, 'updateStatus'])->name('orders.update-status');

    // Settings
    Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
    Route::post('/settings', [SettingController::class, 'update'])->name('settings.update');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
