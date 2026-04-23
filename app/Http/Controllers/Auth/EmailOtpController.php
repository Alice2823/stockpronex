<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\EmailOtp;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class EmailOtpController extends Controller
{

    /*
    |--------------------------------------------------------------------------
    | SEND OTP
    |--------------------------------------------------------------------------
    */
     public function sendOtp(Request $request)
{
    try {

        $request->validate([
            'email' => 'required|email'
        ]);

        $otp = rand(100000, 999999);

        EmailOtp::updateOrCreate(
            ['email' => $request->email],
            [
                'otp' => $otp,
                'expires_at' => Carbon::now()->addMinutes(10)
            ]
        );

        \Log::info("OTP for ".$request->email." is ".$otp);

        \Log::info("TRYING TO SEND OTP");

        Mail::raw("Your StockProNex OTP is: $otp", function ($message) use ($request) {
            $message->to($request->email)
                    ->subject("StockProNex Email OTP");
        });

        \Log::info("MAIL SENT SUCCESSFULLY");

        return response()->json([
            'success' => true,
            'message' => 'OTP sent successfully'
        ]);

    } catch (\Exception $e) {

        \Log::error("OTP ERROR: ".$e->getMessage());

        return response()->json([
            'success' => false,
            'message' => $e->getMessage()
        ]);
    }
}
    /*
    |--------------------------------------------------------------------------
    | REGISTER USER
    |--------------------------------------------------------------------------
    */
    public function registerWithOtp(Request $request)
    {
        try {

            $request->validate([
                'first_name' => 'required',
                'last_name' => 'required',
                'email' => 'required|email',
                'password' => 'required|min:6',
                'otp' => 'required',
                'business_type' => 'nullable|string'
            ]);

            $otp = EmailOtp::where('email', $request->email)
                ->where('otp', $request->otp)
                ->first();

            if (!$otp) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid OTP'
                ]);
            }

            if (Carbon::now()->gt($otp->expires_at)) {
                return response()->json([
                    'success' => false,
                    'message' => 'OTP expired'
                ]);
            }

            $user = User::create([
                'name' => $request->first_name . ' ' . $request->last_name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'business_type' => $request->business_type
            ]);

            EmailOtp::where('email', $request->email)->delete();

            Auth::login($user);

            return response()->json([
                'success' => true,
                'redirect' => '/dashboard'
            ]);

        } catch (\Exception $e) {

            Log::error("REGISTER ERROR: " . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }
}