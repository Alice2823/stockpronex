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
use App\Http\Controllers\LegalController;

/*
|--------------------------------------------------------------------------
| LEGAL ROUTES
|--------------------------------------------------------------------------
*/

Route::get('/terms', [LegalController::class, 'terms'])->name('legal.terms');
Route::get('/privacy', [LegalController::class, 'privacy'])->name('legal.privacy');


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

    Route::post('subscription/create-order', [SubscriptionController::class, 'createOrder'])
        ->name('subscription.create-order');

    Route::post('subscription/verify-payment', [SubscriptionController::class, 'verifyPayment'])
        ->name('subscription.verify-payment');


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

// TEST MAIL ROUTE
Route::get('/test-mail', function () {
    try {
        \Illuminate\Support\Facades\Mail::raw('This is a test email from StockProNex using Brevo.', function ($message) {
            $message->to('pshlok594@gmail.com')->subject('Brevo SMTP Test');
        });
        return "Test email sent successfully! ✅ Check your inbox.";
    } catch (\Exception $e) {
        return "Error sending test email: ❌ " . $e->getMessage();
    }
});

// CACHE CLEAR ROUTE (For Live Server)
Route::get('/clear-cache', function() {
    try {
        \Illuminate\Support\Facades\Artisan::call('config:clear');
        \Illuminate\Support\Facades\Artisan::call('cache:clear');
        \Illuminate\Support\Facades\Artisan::call('view:clear');
        \Illuminate\Support\Facades\Artisan::call('route:clear');
        return "All caches cleared successfully! 🚀";
    } catch (\Exception $e) {
        return "Error clearing cache: " . $e->getMessage();
    }
});

// RAZORPAY TEST ROUTE
Route::get('/test-razorpay', function () {
    try {
        $key = config('app.razorpay_key');
        $secret = config('app.razorpay_secret');
        
        if (!$key || !$secret) {
            return response()->json([
                'status' => 'error',
                'message' => 'Razorpay keys are missing in config!',
                'key_loaded' => $key ? 'Yes (starts with ' . substr($key, 0, 8) . ')' : 'No',
                'secret_loaded' => $secret ? 'Yes' : 'No',
                'env_names_expected' => ['RAZORPAY_KEY', 'RAZORPAY_SECRET']
            ]);
        }

        $api = new \Razorpay\Api\Api($key, $secret);
        $order = $api->order->create([
            'receipt' => 'test_rcpt_' . time(),
            'amount' => 100, // 1 INR
            'currency' => 'INR'
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Razorpay Order Created Successfully!',
            'order_id' => $order['id'],
            'key_used' => substr($key, 0, 8) . '...'
        ]);
    } catch (\Exception $e) {
        \Illuminate\Support\Facades\Log::error('Razorpay Test Error: ' . $e->getMessage());
        return response()->json([
            'status' => 'error',
            'message' => $e->getMessage(),
            'key_used' => isset($key) ? substr($key, 0, 8) . '...' : 'none'
        ]);
    }
});

// LOG VIEWER ROUTE
Route::get('/view-logs', function () {
    $path = storage_path('logs/laravel.log');
    if (!file_exists($path)) {
        return "Log file not found! ❌";
    }
    $logs = file_get_contents($path);
    return "<pre>" . htmlspecialchars(substr($logs, -5000)) . "</pre>";
});

// TEST LOW STOCK ALERT ROUTE
Route::get('/test-low-stock-alert', function () {
    try {
        $user = \Illuminate\Support\Facades\Auth::user();
        if (!$user) {
            return "Please log in first! ❌";
        }

        // Create a dummy stock object for the notification
        $dummyStock = new \stdClass();
        $dummyStock->name = "Test Product (Diagnostic)";
        $dummyStock->quantity = 5;

        $user->notify(new \App\Notifications\LowStockNotification($dummyStock));

        return "Low Stock Alert Test Email Sent! ✅ Check your inbox: " . $user->email;
    } catch (\Exception $e) {
        \Illuminate\Support\Facades\Log::error('Low Stock Alert Test Error: ' . $e->getMessage());
        return "Low Stock Alert Error: ❌ " . $e->getMessage();
    }
});


