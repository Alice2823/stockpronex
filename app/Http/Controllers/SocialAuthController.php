<?php

namespace App\Http\Controllers;

use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Exception;

class SocialAuthController extends Controller
{
    public function redirectToProvider($provider)
    {
        abort_unless($provider === 'google', 404);

        return $this->redirectToGoogle();
    }

    public function handleProviderCallback($provider)
    {
        abort_unless($provider === 'google', 404);

        return $this->handleGoogleCallback();
    }

    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }


    public function handleGoogleCallback()
    {

        try {

            $googleUser = Socialite::driver('google')->user();


            // find existing user
            $user = User::where('email', $googleUser->email)->first();


            if (!$user)
            {

                // create new user
                $user = User::create([

                    'name' => $googleUser->name,

                    'email' => $googleUser->email,

                    'password' => Hash::make('google_login'),

                    'provider_id' => $googleUser->id,

                    'provider_name' => 'google',

                ]);

            }
            else
            {

                // update provider info
                $user->update([

                    'provider_id' => $googleUser->id,

                    'provider_name' => 'google',

                ]);

            }


            Auth::login($user);

            // Save to recent logins cookie
            $recentLogins = [];
            $cookie = request()->cookie('recent_logins');
            if ($cookie) {
                $recentLogins = json_decode($cookie, true) ?: [];
            }
            $recentLogins = array_values(array_filter($recentLogins, function ($login) use ($user) {
                return $login['email'] !== $user->email;
            }));
            array_unshift($recentLogins, [
                'name' => $user->name ?? ($user->first_name . ' ' . $user->last_name),
                'email' => $user->email,
                'initial' => strtoupper(substr($user->first_name ?? $user->name ?? 'U', 0, 1)),
                'business_name' => $user->business_name,
            ]);
            $recentLogins = array_slice($recentLogins, 0, 5);
            $cookieObj = \Illuminate\Support\Facades\Cookie::make('recent_logins', json_encode($recentLogins), 525600, '/', null, false, false);

            return redirect('/dashboard')->withCookie($cookieObj);

        }
        catch (Exception $e)
        {
            return "Google Login Error: " . $e->getMessage();
        }

    }

}
