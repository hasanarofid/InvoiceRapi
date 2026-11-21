<?php

use App\Http\Controllers\Admin\AdminVerificationController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\LeadController;
use App\Http\Controllers\Admin\OrderManagerController;
use App\Http\Controllers\Admin\ProductManagerController;
use App\Http\Controllers\Admin\RoadmapController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\SubscriptionPaymentController;
use App\Http\Controllers\Admin\TrialTrackerController;
use App\Http\Controllers\BillingController;
use App\Http\Controllers\BankAccountController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\OnboardingController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\SynbarraController;
use App\Http\Controllers\WorkspaceDashboardController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| SYNBARRA TECHNOLOGIES — Corporate Website Routes
| Domain: synbarra.com
|--------------------------------------------------------------------------
*/

Route::get('/', [SynbarraController::class, 'home'])->name('home');
Route::get('/i/{token}', [InvoiceController::class, 'publicShow'])->name('invoices.public');
Route::get('/i/{token}/pdf', [InvoiceController::class, 'downloadPdf'])->name('invoices.public.pdf');
Route::get('/about', [SynbarraController::class, 'about'])->name('about');
Route::get('/services', [SynbarraController::class, 'services'])->name('services');
Route::get('/produk', [ProductController::class, 'index'])->name('products.index');
Route::get('/produk/{slug}', [ProductController::class, 'show'])->name('products.show');
Route::get('/approach', [SynbarraController::class, 'approach'])->name('approach');
Route::get('/portofolio', [SynbarraController::class, 'portfolio'])->name('portfolio');
Route::get('/portfolio', fn () => redirect()->route('portfolio', [], 301));
Route::get('/contact', [SynbarraController::class, 'contact'])->name('contact');
Route::post('/contact', [SynbarraController::class, 'submitContact'])->name('contact.submit');
Route::get('/sitemap.xml', [SynbarraController::class, 'sitemap'])->name('sitemap');
Route::get('/llms.txt', [SynbarraController::class, 'llmsTxt'])->name('llms');
Route::get('/template-showcase', fn () => redirect()->route('products.index', [], 301))->name('template.showcase');

/*
|--------------------------------------------------------------------------
| Admin & Management Portal Routes
|--------------------------------------------------------------------------
*/

// Redirect /admin & /dashboard based on auth state
Route::get('/admin', function () {
    return auth()->check() ? redirect()->route('admin.dashboard') : redirect()->route('login');
})->name('admin');

Route::get('/dashboard', [WorkspaceDashboardController::class, 'index'])
    ->middleware(['auth', 'onboarded'])
    ->name('dashboard');

Route::middleware(['auth', 'onboarded', 'admin.verified'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Verifikasi Pembayaran Transfer Bank (Upgrade Paket)
    Route::get('/payments', [SubscriptionPaymentController::class, 'index'])->name('payments.index');
    Route::post('/payments/{payment}/verify', [SubscriptionPaymentController::class, 'verify'])->name('payments.verify');

    // Roadmap Pengembangan & Akuisisi Klien (Sprint Tahap 1 - 5)
    Route::resource('roadmap', RoadmapController::class);
    Route::patch('/roadmap/{roadmap}/status', [RoadmapController::class, 'updateStatus'])->name('roadmap.update-status');

    // Lead Management & Inbox Prospek
    Route::resource('leads', LeadController::class);

    // Products / Service Catalog Management
    Route::resource('products', ProductManagerController::class);

    // Orders / Inquiries Management
    Route::get('/orders', [OrderManagerController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [OrderManagerController::class, 'show'])->name('orders.show');
    Route::patch('/orders/{order}/status', [OrderManagerController::class, 'updateStatus'])->name('orders.update-status');

    // Settings
    Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
    Route::post('/settings', [SettingController::class, 'update'])->name('settings.update');

    // Trial Tracker & WhatsApp Follow-up (CRM Superadmin)
    Route::get('/trial-tracker', [TrialTrackerController::class, 'index'])->name('trial-tracker.index');
    Route::post('/trial-tracker/{tenant}/follow-up', [TrialTrackerController::class, 'updateFollowUp'])->name('trial-tracker.follow-up');
});

Route::middleware(['auth', 'onboarded'])->group(function () {
    // Workspace SaaS Routes
    Route::resource('clients', ClientController::class)->only(['index', 'store', 'update', 'destroy']);
    Route::resource('projects', ProjectController::class)->only(['index', 'store', 'update', 'destroy']);
    Route::resource('invoices', InvoiceController::class)->only(['index', 'create', 'store', 'show', 'destroy']);
    Route::post('/invoices/{invoice}/payments', [PaymentController::class, 'store'])->name('payments.store');

    // Billing & Upload Bukti Pembayaran Paket
    Route::get('/billing', [BillingController::class, 'index'])->name('billing.index');
    Route::post('/billing', [BillingController::class, 'store'])->name('billing.store');

    // Rekening Pembayaran Invoice Member
    Route::post('/bank-accounts', [BankAccountController::class, 'store'])->name('bank-accounts.store');
    Route::put('/bank-accounts/{bankAccount}', [BankAccountController::class, 'update'])->name('bank-accounts.update');
    Route::patch('/bank-accounts/{bankAccount}/default', [BankAccountController::class, 'setDefault'])->name('bank-accounts.default');
    Route::delete('/bank-accounts/{bankAccount}', [BankAccountController::class, 'destroy'])->name('bank-accounts.destroy');

    // Profil & Logo Usaha Member
    Route::post('/profile/business', [ProfileController::class, 'updateBusiness'])->name('profile.business.update');
});

Route::middleware(['auth'])->group(function () {
    // Verifikasi Login Admin (2-Step Email OTP)
    Route::get('/admin/verify-login', [AdminVerificationController::class, 'show'])->name('admin.verify.show');
    Route::post('/admin/verify-login', [AdminVerificationController::class, 'verify'])->name('admin.verify.store');
    Route::post('/admin/verify-login/resend', [AdminVerificationController::class, 'resend'])
        ->middleware('throttle:5,1')
        ->name('admin.verify.resend');

    // Multi-tenant Business Onboarding (Wajib input WhatsApp & Profil Bisnis)
    Route::get('/onboarding', [OnboardingController::class, 'index'])->name('onboarding.index');
    Route::post('/onboarding', [OnboardingController::class, 'store'])->name('onboarding.store');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
