<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     *
     * @return \Illuminate\View\View
     */
    public function create(Request $request)
    {
        // Read recent logins from cookie
        $recentLogins = [];
        $cookie = $request->cookie('recent_logins');
        if ($cookie) {
            $recentLogins = json_decode($cookie, true) ?: [];
        }

        return view('auth.login', compact('recentLogins'));
    }

    /**
     * Handle an incoming authentication request.
     *
     * @param  \App\Http\Requests\Auth\LoginRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(LoginRequest $request)
    {
        // 🔐 Cloudflare Turnstile verification
        $response = \Illuminate\Support\Facades\Http::asForm()->post(
            'https://challenges.cloudflare.com/turnstile/v0/siteverify',
            [
                'secret' => config('services.turnstile.secret_key'),
                'response' => $request->input('cf-turnstile-response'),
                'remoteip' => $request->ip(),
            ]
        );

        if (!($response->json()['success'] ?? false)) {
            return back()->withErrors(['captcha' => 'Captcha verification failed. Please try again.'])->withInput();
        }

        $request->authenticate();

        $request->session()->regenerate();

        // Save to recent logins cookie
        $user = Auth::user();
        $recentLogins = [];
        $cookie = $request->cookie('recent_logins');
        if ($cookie) {
            $recentLogins = json_decode($cookie, true) ?: [];
        }

        // Remove existing entry for this email (to avoid duplicates)
        $recentLogins = array_values(array_filter($recentLogins, function ($login) use ($user) {
            return $login['email'] !== $user->email;
        }));

        // Add current user to the top
        array_unshift($recentLogins, [
            'name' => $user->name ?? ($user->first_name . ' ' . $user->last_name),
            'email' => $user->email,
            'initial' => strtoupper(substr($user->first_name ?? $user->name, 0, 1)),
            'business_name' => $user->business_name,
        ]);

        // Keep only last 5 recent logins
        $recentLogins = array_slice($recentLogins, 0, 5);

        // Set cookie for 1 year (525600 minutes)
        $cookieObj = Cookie::make('recent_logins', json_encode($recentLogins), 525600, '/', null, false, false);

        return redirect()->intended(RouteServiceProvider::HOME)->withCookie($cookieObj);
    }

    /**
     * Destroy an authenticated session.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }

    /**
     * Remove a recent login entry by email.
     */
    public function removeRecentLogin(Request $request)
    {
        $emailToRemove = $request->input('email');
        $recentLogins = [];
        $cookie = $request->cookie('recent_logins');
        if ($cookie) {
            $recentLogins = json_decode($cookie, true) ?: [];
        }

        $recentLogins = array_values(array_filter($recentLogins, function ($login) use ($emailToRemove) {
            return $login['email'] !== $emailToRemove;
        }));

        $cookieObj = Cookie::make('recent_logins', json_encode($recentLogins), 525600, '/', null, false, false);

        return response()->json(['success' => true])->withCookie($cookieObj);
    }
}
