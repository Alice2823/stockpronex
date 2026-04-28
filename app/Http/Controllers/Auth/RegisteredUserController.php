<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use App\Constants\BusinessIndustry;

class RegisteredUserController extends Controller
{
    public function create()
    {
        return view('auth.register');
    }
    public function store(Request $request)
    {
        $request->validate([
            'first_name' => ['required','string','max:255'],
            'last_name'  => ['required','string','max:255'],
            'email'      => ['required','string','email','max:255','unique:users'],
            'password'   => ['required','confirmed', Rules\Password::defaults()],
            'business_type' => ['nullable','string'],
        ]);

        $taxRates = BusinessIndustry::getTaxRates($request->business_type ?? 'General Store(Kirana)');

        $user = User::create([
            'first_name' => trim($request->first_name),
            'last_name'  => trim($request->last_name),
            'name'       => trim($request->first_name).' '.trim($request->last_name),
            'email'      => strtolower($request->email),
            'password'   => Hash::make($request->password),
            'business_type' => $request->business_type ?? 'General Store(Kirana)',
            'tds_percentage' => $taxRates['tds'],
            'tcs_percentage' => $taxRates['tcs'],
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect()->route('dashboard');
    }
}