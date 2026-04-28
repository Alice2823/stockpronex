<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request)
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(Request $request)
    {
        $request->validate([
            'first_name' => ['sometimes', 'required', 'string', 'max:255'],
            'last_name' => ['sometimes', 'required', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:20'],
            'business_type' => ['nullable', 'string', 'max:255'],
            'business_name' => ['nullable', 'string', 'max:255'],
            'address' => ['nullable', 'string'],
            'language' => ['nullable', 'string', 'max:10'],
            'currency' => ['nullable', 'string', 'max:10'],
            'tax_number' => ['nullable', 'string', 'max:255'],
            'tax_percentage' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'payment_id' => ['nullable', 'string', 'max:255'],
            'invoice_color' => ['nullable', 'string', 'max:7', 'regex:/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/'],
            'bank_name' => ['nullable', 'string', 'max:255'],
            'account_number' => ['nullable', 'string', 'max:255'],
            'ifsc_code' => ['nullable', 'string', 'max:255'],
            'upi_id' => ['nullable', 'string', 'max:255'],
            'ca_sharing_enabled' => ['sometimes', 'boolean'],
            'ca_name' => ['nullable', 'string', 'max:255'],
            'ca_whatsapp' => ['nullable', 'string', 'max:20'],
            'ca_email' => ['nullable', 'email', 'max:255'],
        ]);

        $user = $request->user();
        
        if ($request->has('first_name')) $user->first_name = $request->first_name;
        if ($request->has('last_name')) $user->last_name = $request->last_name;
        
        if ($request->has('first_name') || $request->has('last_name')) {
            $user->name = trim(($request->first_name ?? $user->first_name) . ' ' . ($request->last_name ?? $user->last_name));
        }

        if ($request->has('phone')) $user->phone = $request->phone;
        if ($request->has('business_type')) $user->business_type = $request->business_type;
        if ($request->has('business_name')) $user->business_name = $request->business_name;
        if ($request->has('address')) $user->address = $request->address;
        if ($request->has('language')) $user->language = $request->language;
        if ($request->has('currency')) $user->currency = $request->currency;
        if ($request->has('tax_number')) $user->tax_number = $request->tax_number;
        if ($request->has('tax_percentage')) $user->tax_percentage = $request->tax_percentage;
        if ($request->has('payment_id')) $user->payment_id = $request->payment_id;
        if ($request->has('invoice_color')) $user->invoice_color = $request->invoice_color;
        if ($request->has('bank_name')) $user->bank_name = $request->bank_name;
        if ($request->has('account_number')) $user->account_number = $request->account_number;
        if ($request->has('ifsc_code')) $user->ifsc_code = $request->ifsc_code;
        if ($request->has('upi_id')) $user->upi_id = $request->upi_id;

        $user->ca_sharing_enabled = $request->boolean('ca_sharing_enabled');
        if ($request->has('ca_name')) $user->ca_name = $request->ca_name;
        if ($request->has('ca_whatsapp')) $user->ca_whatsapp = $request->ca_whatsapp;
        if ($request->has('ca_email')) $user->ca_email = $request->ca_email;

        $user->save();

        return back()->with('status', 'profile-updated');
    }

    /**
     * Update the user's password.
     */
    public function updatePassword(Request $request)
    {
        $validated = $request->validateWithBag('updatePassword', [
            'current_password' => ['required', 'current_password'],
            'password' => ['required', Password::defaults(), 'confirmed'],
        ]);

        $request->user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        return back()->with('status', 'password-updated');
    }
    /**
     * Delete the user's account.
     */
    public function destroy(Request $request)
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        auth()->logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
