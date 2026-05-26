<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\EmailOtp;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;

class EmailOtpController extends Controller
{
    public function sendOtp(Request $request)
    {
        try {
            $request->validate([
                'email' => 'required|email',
            ]);

            $email = strtolower(trim($request->email));
            $rateLimitKey = 'send-otp:' . $email . '|' . $request->ip();

            if (RateLimiter::tooManyAttempts($rateLimitKey, 3)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Too many OTP requests. Please try again in ' . RateLimiter::availableIn($rateLimitKey) . ' seconds.',
                ], 429);
            }

            RateLimiter::hit($rateLimitKey, 60);

            $otp = (string) random_int(100000, 999999);

            EmailOtp::updateOrCreate(
                ['email' => $email],
                [
                    'otp' => Hash::make($otp),
                    'expires_at' => Carbon::now()->addMinutes(10),
                ]
            );

            Mail::raw("Your StockProNex OTP is: {$otp}", function ($message) use ($email) {
                $message->to($email)->subject('StockProNex Email OTP');
            });

            return response()->json([
                'success' => true,
                'message' => 'OTP sent successfully',
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->validator->errors()->first(),
            ], 422);
        } catch (\Exception $e) {
            Log::error('OTP send failed', ['email' => $request->input('email'), 'error' => $e->getMessage()]);

            return response()->json([
                'success' => false,
                'message' => 'Unable to send OTP right now. Please try again.',
            ], 500);
        }
    }

    public function registerWithOtp(Request $request)
    {
        try {
            $request->validate([
                'first_name' => 'required|string|max:255',
                'last_name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|min:6',
                'otp' => 'required|string',
                'business_type' => 'nullable|string|max:255',
            ]);

            $email = strtolower(trim($request->email));
            $rateLimitKey = 'register-with-otp:' . $email . '|' . $request->ip();

            if (RateLimiter::tooManyAttempts($rateLimitKey, 5)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Too many registration attempts. Please try again in ' . RateLimiter::availableIn($rateLimitKey) . ' seconds.',
                ], 429);
            }

            RateLimiter::hit($rateLimitKey, 60);

            $otpRecord = EmailOtp::where('email', $email)->first();

            if (!$otpRecord || !$this->otpMatches($request->otp, $otpRecord->otp)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid OTP',
                ], 422);
            }

            if (Carbon::now()->gt($otpRecord->expires_at)) {
                $otpRecord->delete();

                return response()->json([
                    'success' => false,
                    'message' => 'OTP expired',
                ], 422);
            }

            $user = User::create([
                'first_name' => trim($request->first_name),
                'last_name' => trim($request->last_name),
                'name' => trim($request->first_name) . ' ' . trim($request->last_name),
                'email' => $email,
                'password' => Hash::make($request->password),
                'business_type' => $request->business_type,
            ]);

            $otpRecord->delete();
            RateLimiter::clear($rateLimitKey);

            Auth::login($user);

            return response()->json([
                'success' => true,
                'redirect' => '/dashboard',
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->validator->errors()->first(),
            ], 422);
        } catch (\Exception $e) {
            Log::error('OTP registration failed', ['email' => $request->input('email'), 'error' => $e->getMessage()]);

            return response()->json([
                'success' => false,
                'message' => 'Registration failed. Please try again.',
            ], 500);
        }
    }

    private function otpMatches(string $plainOtp, string $storedOtp): bool
    {
        if (Hash::check($plainOtp, $storedOtp)) {
            return true;
        }

        return preg_match('/^\d{6}$/', $storedOtp) === 1 && hash_equals($storedOtp, $plainOtp);
    }
}
