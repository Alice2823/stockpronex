<?php

namespace App\Http\Controllers;

use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Exception;

class SocialAuthController extends Controller
{

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

            return redirect('/dashboard');

        }
        catch (Exception $e)
        {
            return "Google Login Error: " . $e->getMessage();
        }

    }

}