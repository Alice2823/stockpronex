<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Razorpay\Api\Api;
use Illuminate\Support\Facades\Auth;
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
            
            $order = $api->order->create([
                'receipt' => 'rcpt_' . Auth::id() . '_' . $request->plan . '_' . $request->cycle . '_' . time(),
                'amount' => $amount,
                'currency' => 'INR',
                'notes' => [
                    'plan' => $request->plan,
                    'cycle' => $request->cycle,
                    'user_id' => Auth::id()
                ]
            ]);

            \Illuminate\Support\Facades\Log::info('Razorpay Key Prefix: ' . substr(config('app.razorpay_key'), 0, 10));

            return response()->json([
                'order_id' => $order['id'],
                'amount' => (int) $amount,
                'currency' => 'INR',
                'razorpay_key' => config('app.razorpay_key')
            ]);
        } catch (Exception $e) {
            return response()->json(['error' => 'Could not create order: ' . $e->getMessage()], 500);
        }
    }

    public function verifyPayment(Request $request)
    {
        // Only allow mock payments if Developer Mode is enabled in config
        $devMode = config('app.developer_mode');
        
        if (!$devMode) {
            $request->validate([
                'razorpay_payment_id' => 'required',
                'razorpay_order_id' => 'required',
                'razorpay_signature' => 'required',
                'plan' => 'required',
                'cycle' => 'required',
            ]);

            try {
                $api = new Api(config('app.razorpay_key'), config('app.razorpay_secret'));
                $attributes = [
                    'razorpay_order_id' => $request->razorpay_order_id,
                    'razorpay_payment_id' => $request->razorpay_payment_id,
                    'razorpay_signature' => $request->razorpay_signature
                ];
                $api->utility->verifyPaymentSignature($attributes);
            } catch (Exception $e) {
                if ($request->ajax()) {
                    return response()->json(['error' => 'Payment verification failed: ' . $e->getMessage()], 400);
                }
                return back()->withErrors(['payment' => 'Payment verification failed: ' . $e->getMessage()]);
            }
        }

        $plan = $request->plan;
        $cycle = $request->cycle;

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

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Successfully upgraded to ' . ucfirst($plan) . ' (' . ucfirst($cycle) . ') plan!',
                'redirect' => route('dashboard')
            ]);
        }

        return redirect()->route('dashboard')->with('success', 'Successfully upgraded to ' . ucfirst($plan) . ' (' . ucfirst($cycle) . ') plan!');
    }
}
