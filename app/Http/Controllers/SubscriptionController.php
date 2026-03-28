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
        
        // Pre-create orders for each plan/cycle to simplify the frontend
        $orders = [];
        if (!config('app.developer_mode') && $razorpayKey) {
            try {
                $api = new Api($razorpayKey, config('app.razorpay_secret'));
                
                foreach (['standard', 'pro'] as $plan) {
                    foreach (['monthly', 'yearly'] as $cycle) {
                        $amount = $prices[$plan][$cycle] * 100; // to paise
                        $order = $api->order->create([
                            'receipt' => 'rcpt_' . Auth::id() . '_' . $plan . '_' . $cycle,
                            'amount' => $amount,
                            'currency' => 'INR',
                            'notes' => [
                                'plan' => $plan,
                                'cycle' => $cycle,
                                'user_id' => Auth::id()
                            ]
                        ]);
                        $orders[$plan][$cycle] = $order['id'];
                    }
                }
            } catch (Exception $e) {
                // Log or handle error if needed
            }
        }

        return view('subscription.index', compact('orders'));
    }

    public function payment(Request $request)
    {
        // Only allow mock payments if Developer Mode is enabled in config
        $devMode = config('app.developer_mode');
        
        $rules = [
            'plan' => 'required|in:standard,pro',
            'cycle' => 'required|in:monthly,yearly',
        ];

        if (!$devMode) {
            $request->validate([
                'razorpay_payment_id' => 'required',
                'razorpay_order_id' => 'required',
                'razorpay_signature' => 'required',
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

        return redirect()->route('dashboard')->with('success', 'Successfully upgraded to ' . ucfirst($plan) . ' (' . ucfirst($cycle) . ') plan!');
    }
}
