<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\StockController;
use App\Http\Controllers\StockUsageController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\SocialAuthController;
use App\Http\Controllers\Auth\EmailOtpController;
use App\Http\Controllers\StockBarcodeController;
use App\Http\Controllers\StockBarcodeUsageController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\WhatsappController;
use App\Http\Controllers\AiAssistantController;


/*
|--------------------------------------------------------------------------
| HOME
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
});


/*
|--------------------------------------------------------------------------
| EMAIL OTP ROUTES
|--------------------------------------------------------------------------
| These routes handle OTP sending and registration
*/

Route::post('/send-otp', [EmailOtpController::class, 'sendOtp'])
    ->name('send.otp');

Route::post('/register-with-otp', [EmailOtpController::class, 'registerWithOtp'])
    ->name('register.otp');


/*
|--------------------------------------------------------------------------
| GOOGLE LOGIN ROUTES
|--------------------------------------------------------------------------
*/

Route::get('/auth/google/redirect', [SocialAuthController::class, 'redirectToGoogle'])
    ->name('google.redirect');

Route::get('/auth/google/callback', [SocialAuthController::class, 'handleGoogleCallback'])
    ->name('google.callback');


/*
|--------------------------------------------------------------------------
| AUTHENTICATED ROUTES
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->group(function () {


    /*
    |--------------------------------------------------------------------------
    | DASHBOARD
    |--------------------------------------------------------------------------
    */

    Route::get('/dashboard', [StockController::class, 'index'])
        ->name('dashboard');

    Route::get('/dashboard/analytics', [App\Http\Controllers\DashboardController::class, 'index'])
        ->name('dashboard.analytics');
    
    Route::get('/dashboard/analytics/data', [App\Http\Controllers\DashboardController::class, 'getData'])
        ->name('dashboard.analytics.data');
    
    Route::get('/dashboard/analytics/export', [App\Http\Controllers\DashboardController::class, 'export'])
        ->name('dashboard.analytics.export');

    Route::get('/dashboard/payments', [App\Http\Controllers\PaymentController::class, 'index'])
        ->name('dashboard.payments');
    
    Route::get('/dashboard/payments/export/csv', [App\Http\Controllers\PaymentController::class, 'exportCsv'])
        ->name('dashboard.payments.export.csv');
        
    Route::get('/dashboard/payments/export/pdf', [App\Http\Controllers\PaymentController::class, 'exportPdf'])
        ->name('dashboard.payments.export.pdf');

    Route::get('/dashboard/payments/analytics-data', [App\Http\Controllers\PaymentController::class, 'getAnalyticsData'])
        ->name('dashboard.payments.analytics');

    Route::get('/dashboard/profit', [App\Http\Controllers\ProfitController::class, 'index'])
        ->name('dashboard.profit');
    
    Route::get('/dashboard/profit/export/pdf', [App\Http\Controllers\ProfitController::class, 'exportPdf'])
        ->name('dashboard.profit.export.pdf');

    Route::get('/dashboard/profit/export/excel', [App\Http\Controllers\ProfitController::class, 'exportExcel'])
        ->name('dashboard.profit.export.excel');




    /*
    |--------------------------------------------------------------------------
    | STOCK ROUTES
    |--------------------------------------------------------------------------
    */

    Route::get('stocks/export/{type}', [StockController::class, 'export'])
        ->name('stocks.export');

    Route::resource('stocks', StockController::class)
        ->except(['index']);


    /*
    |--------------------------------------------------------------------------
    | STOCK USAGE ROUTES
    |--------------------------------------------------------------------------
    */

    Route::get('usage/export/{type}', [StockUsageController::class, 'export'])
        ->name('usage.export');

    Route::resource('usage', StockUsageController::class)
        ->only(['index','create','store']);


    /*
    |--------------------------------------------------------------------------
    | SUBSCRIPTION ROUTES
    |--------------------------------------------------------------------------
    */

    Route::get('subscription', [SubscriptionController::class, 'index'])
        ->name('subscription.index');

    Route::post('subscription/payment', [SubscriptionController::class, 'payment'])
        ->name('subscription.payment');


    /*
    |--------------------------------------------------------------------------
    | BARCODE ROUTES
    |--------------------------------------------------------------------------
    */

    Route::get('stock/{id}/barcode', [StockBarcodeController::class, 'index'])
        ->name('stock.barcode.index');

    Route::post('stock/{id}/barcode/generate', [StockBarcodeController::class, 'generate'])
        ->name('stock.barcode.generate');

    Route::get('barcode/scan', [StockBarcodeController::class, 'scan'])
        ->name('barcode.scan');

    Route::post('barcode/mark-used', [StockBarcodeController::class, 'markUsed'])
        ->name('barcode.mark-used');

    /*
    |--------------------------------------------------------------------------
    | STOCK USAGE VIA BARCODE ROUTES
    |--------------------------------------------------------------------------
    */

    Route::get('usage/barcode', [StockBarcodeUsageController::class, 'index'])
        ->name('usage.barcode');

    Route::post('usage/barcode/scan', [StockBarcodeUsageController::class, 'scan'])
        ->name('usage.barcode.scan');

    Route::post('/usage/barcode/use', [StockBarcodeUsageController::class, 'processBarcode'])->name('usage.barcode.use');
    Route::post('/usage/barcode/multi', [StockBarcodeUsageController::class, 'useBarcodeMulti'])->name('usage.barcode.multi');
    Route::post('usage/barcode/manual', [StockBarcodeUsageController::class, 'manualUse'])
        ->name('usage.barcode.manual');

    Route::get('barcode/{barcode}/details', [StockBarcodeUsageController::class, 'getDetails'])
        ->name('barcode.details');

    Route::get('invoice/{id}/download', [InvoiceController::class, 'download'])
        ->name('invoice.download');

    Route::post('invoice/{id}/whatsapp/send', [WhatsappController::class, 'send'])
        ->name('invoice.whatsapp.send');

    Route::post('invoice/{id}/whatsapp/resend', [WhatsappController::class, 'resend'])
        ->name('invoice.whatsapp.resend');

    /*
    |--------------------------------------------------------------------------
    | AI ASSISTANT ROUTES
    |--------------------------------------------------------------------------
    */

    Route::post('/ai/chat', [AiAssistantController::class, 'chat'])
        ->name('ai.chat');

    Route::get('/ai/suggestions', [AiAssistantController::class, 'suggestions'])
        ->name('ai.suggestions');

    /*
    |--------------------------------------------------------------------------
    | PROFILE ROUTES
    |--------------------------------------------------------------------------
    */
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

});


/*
|--------------------------------------------------------------------------
| DEFAULT LARAVEL AUTH ROUTES
|--------------------------------------------------------------------------
*/

require __DIR__.'/auth.php';



Route::post('/send-otp', [EmailOtpController::class, 'sendOtp']);
Route::post('/register-with-otp', [EmailOtpController::class, 'registerWithOtp']);

Route::get('/make-me-pro', function () {
    $user = \App\Models\User::where('email', 'patelalice266@gmail.com')->first();

    if ($user) {
        $user->subscription = 'pro';
        $user->save();
        return "User upgraded to PRO ✅";
    }

    return "User not found ❌";
});