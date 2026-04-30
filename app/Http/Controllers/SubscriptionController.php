<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Razorpay\Api\Api;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Exception;

class SubscriptionController extends Controller
{
    public function index()
    {
        $prices = \App\Constants\Plan::PRICES;
        $razorpayKey = config('app.razorpay_key');
        
        return view('subscription.index');
    }

    public function createOrder(Request $request)
    {
        $request->validate([
            'plan' => 'required|in:standard,pro',
            'cycle' => 'required|in:monthly,yearly',
        ]);

        $prices = \App\Constants\Plan::PRICES;
        $amount = $prices[$request->plan][$request->cycle] * 100; // to paise

        if ($amount < 100) {
            return response()->json(['error' => 'Amount must be at least 100 paise.'], 400);
        }

        try {
            $api = new Api(config('app.razorpay_key'), config('app.razorpay_secret'));
            $plan = $request->plan;
            $cycle = $request->cycle;
            
            $order = $api->order->create([
                'receipt' => 'rcpt_' . Auth::id() . '_' . $plan . '_' . $cycle . '_' . time(),
                'amount' => $amount,
                'currency' => 'INR',
                'notes' => [
                    'plan' => $plan,
                    'cycle' => $cycle,
                    'user_id' => Auth::id()
                ]
            ]);

            $pendingOrders = $request->session()->get('razorpay_orders', []);
            $pendingOrders[$order['id']] = [
                'user_id' => Auth::id(),
                'plan' => $plan,
                'cycle' => $cycle,
                'amount' => (int) $amount,
                'currency' => 'INR',
                'created_at' => now()->timestamp,
            ];

            $request->session()->put('razorpay_orders', array_slice($pendingOrders, -10, null, true));

            return response()->json([
                'order_id' => $order['id'],
                'amount' => (int) $amount,
                'currency' => 'INR',
                'razorpay_key' => config('app.razorpay_key')
            ]);
        } catch (Exception $e) {
            Log::error('Razorpay Order Creation Failed: ' . $e->getMessage(), [
                'exception' => $e,
                'user_id' => Auth::id(),
                'plan' => $request->plan,
                'amount' => $amount
            ]);
            return response()->json(['error' => 'Could not create order: ' . $e->getMessage()], 500);
        }
    }

    public function verifyPayment(Request $request)
    {
        // Only allow mock payments if Developer Mode is enabled in config
        $devMode = config('app.developer_mode');
        
        $request->validate($devMode ? [
            'plan' => 'required|in:standard,pro',
            'cycle' => 'required|in:monthly,yearly',
        ] : [
            'razorpay_payment_id' => 'required|string',
            'razorpay_order_id' => 'required|string',
            'razorpay_signature' => 'required|string',
        ]);

        $plan = $request->plan;
        $cycle = $request->cycle;

        if (!$devMode) {
            try {
                $api = new Api(config('app.razorpay_key'), config('app.razorpay_secret'));
                $pendingOrder = $request->session()->get('razorpay_orders.' . $request->razorpay_order_id);

                if (!$pendingOrder || (int) $pendingOrder['user_id'] !== Auth::id()) {
                    return $this->paymentFailure($request, 'Payment order expired or does not belong to this account.');
                }

                $attributes = [
                    'razorpay_order_id' => $request->razorpay_order_id,
                    'razorpay_payment_id' => $request->razorpay_payment_id,
                    'razorpay_signature' => $request->razorpay_signature
                ];
                $api->utility->verifyPaymentSignature($attributes);

                $payment = $api->payment->fetch($request->razorpay_payment_id);

                if (($payment['order_id'] ?? null) !== $request->razorpay_order_id) {
                    return $this->paymentFailure($request, 'Payment order mismatch.');
                }

                if ((int) ($payment['amount'] ?? 0) !== (int) $pendingOrder['amount']) {
                    return $this->paymentFailure($request, 'Payment amount mismatch.');
                }

                if (($payment['currency'] ?? null) !== $pendingOrder['currency']) {
                    return $this->paymentFailure($request, 'Payment currency mismatch.');
                }

                if (!in_array(($payment['status'] ?? null), ['authorized', 'captured'], true)) {
                    return $this->paymentFailure($request, 'Payment is not completed.');
                }

                $plan = $pendingOrder['plan'];
                $cycle = $pendingOrder['cycle'];
            } catch (Exception $e) {
                return $this->paymentFailure($request, 'Payment verification failed: ' . $e->getMessage());
            }
        }

        $user = Auth::user();
        $user->plan = $plan;
        $user->billing_cycle = $cycle;
        
        // Calculate end date
        if ($cycle === 'yearly') {
            $user->subscription_ends_at = now()->addYear();
        } else {
            $user->subscription_ends_at = now()->addMonth();
        }
        
        $user->save();
        if ($request->filled('razorpay_order_id')) {
            $request->session()->forget('razorpay_orders.' . $request->razorpay_order_id);
        }

        if ($this->expectsJsonResponse($request)) {
            return response()->json([
                'success' => true,
                'message' => 'Successfully upgraded to ' . ucfirst($plan) . ' (' . ucfirst($cycle) . ') plan!',
                'redirect' => route('dashboard')
            ]);
        }

        return redirect()->route('dashboard')->with('success', 'Successfully upgraded to ' . ucfirst($plan) . ' (' . ucfirst($cycle) . ') plan!');
    }

    private function paymentFailure(Request $request, string $message)
    {
        if ($this->expectsJsonResponse($request)) {
            return response()->json(['error' => $message], 400);
        }

        return back()->withErrors(['payment' => $message]);
    }

    private function expectsJsonResponse(Request $request): bool
    {
        return $request->ajax() || $request->expectsJson() || $request->isJson();
    }
}
