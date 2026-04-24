<x-app-layout>
    <x-slot name="header">
        <h2 class="font-black text-3xl text-gray-800 dark:text-white leading-tight">
            {{ __('Account Settings') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            
            <!-- Profile Information -->
            <div x-data="{ open: false }" class="bg-white dark:bg-gray-900 shadow-2xl rounded-3xl border border-gray-100 dark:border-gray-800 transition-all duration-300 overflow-hidden">
                <button @click="open = !open" class="w-full text-left p-8 focus:outline-none flex justify-between items-center group">
                    <header>
                        <h2 class="text-xl font-black text-gray-900 dark:text-white flex items-center gap-2 group-hover:text-blue-500 transition-colors">
                            <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                            {{ __('Profile Information') }}
                        </h2>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                            {{ __("Update your account's profile information and email address.") }}
                        </p>
                    </header>
                    <svg class="w-6 h-6 transform transition-transform duration-300 text-gray-400" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>

                <div x-show="open" x-collapse x-cloak class="px-8 pb-8">
                    <div class="max-w-xl">
                        <form method="post" action="{{ route('profile.update') }}" class="space-y-6">
                            @csrf
                            @method('patch')

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <x-label for="first_name" :value="__('First Name')" />
                                    <x-input id="first_name" name="first_name" type="text" class="mt-1 block w-full" :value="old('first_name', explode(' ', $user->name)[0])" required autofocus />
                                    <x-input-error class="mt-2" :messages="$errors->get('first_name')" />
                                </div>

                                <div>
                                    <x-label for="last_name" :value="__('Last Name')" />
                                    <x-input id="last_name" name="last_name" type="text" class="mt-1 block w-full" :value="old('last_name', explode(' ', $user->name)[1] ?? '')" required />
                                    <x-input-error class="mt-2" :messages="$errors->get('last_name')" />
                                </div>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <x-label for="email" :value="__('Email')" />
                                    <x-input id="email" type="email" class="mt-1 block w-full bg-gray-50 dark:bg-gray-800 cursor-not-allowed opacity-60" :value="$user->email" readonly />
                                </div>

                                <div>
                                    <x-label for="phone" :value="__('Phone Number')" />
                                    <x-input id="phone" name="phone" type="text" class="mt-1 block w-full" :value="old('phone', $user->phone)" />
                                    <x-input-error class="mt-2" :messages="$errors->get('phone')" />
                                </div>
                            </div>

                            <!-- Subscription Plan Information -->
                            <div class="p-4 bg-gray-50 dark:bg-gray-800/40 rounded-2xl border border-gray-100 dark:border-gray-700 flex items-center justify-between">
                                <div class="flex items-center gap-4">
                                    <div class="p-3 rounded-xl {{ $user->plan === 'pro' ? 'bg-purple-100 dark:bg-purple-900/30 text-purple-600' : ($user->plan === 'standard' ? 'bg-blue-100 dark:bg-blue-900/30 text-blue-600' : 'bg-gray-100 dark:bg-gray-700 text-gray-500') }}">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-widest">{{ __('Current Plan') }}</p>
                                        <div class="flex items-center gap-2">
                                            <span class="text-lg font-black text-gray-900 dark:text-white uppercase">{{ $user->plan ?? 'FREE' }}</span>
                                            @if($user->plan !== 'free')
                                                <span class="text-[10px] px-2 py-0.5 rounded-full bg-green-100 dark:bg-green-900/40 text-green-600 dark:text-green-400 font-bold uppercase">{{ $user->billing_cycle }}</span>
                                            @endif
                                        </div>
                                        @if($user->subscription_ends_at)
                                            <p class="text-[10px] text-gray-400 dark:text-gray-500">
                                                {{ __('Expires on') }}: {{ \Carbon\Carbon::parse($user->subscription_ends_at)->format('M d, Y') }}
                                            </p>
                                        @endif
                                    </div>
                                </div>
                                <a href="{{ route('subscription.index') }}" class="text-sm font-bold text-blue-600 hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-300 flex items-center gap-1 group">
                                    {{ __('Manage / Upgrade') }}
                                    <svg class="w-4 h-4 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                    </svg>
                                </a>
                            </div>

                            <div class="flex items-center gap-4 pt-4">
                                <button type="submit" class="bg-gray-900 dark:bg-blue-600 text-white px-8 py-2.5 rounded-xl font-bold hover:bg-black dark:hover:bg-blue-700 transform active:scale-95 transition-all">
                                    {{ __('Save Profile') }}
                                </button>

                                @if (session('status') === 'profile-updated')
                                    <p
                                        x-data="{ show: true }"
                                        x-show="show"
                                        x-transition
                                        x-init="setTimeout(() => show = false, 2000)"
                                        class="text-sm text-green-600 dark:text-green-400 font-bold"
                                    >{{ __('Saved.') }}</p>
                                @endif
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Business Details -->
            <div x-data="{ 
                open: false,
                currency: '{{ $user->currency ?? 'USD' }}',
                get taxLabel() {
                    return this.currency === 'INR' ? '{{ __('GST Number') }}' : '{{ __('Tax Number / VAT') }}';
                },
                get taxPlaceholder() {
                    return this.currency === 'INR' ? 'e.g. 27AAAAA0000A1Z5' : '{{ __("Enter your tax or VAT number") }}';
                }
            }" class="bg-white dark:bg-gray-900 shadow-2xl rounded-3xl border border-gray-100 dark:border-gray-800 transition-all duration-300 overflow-hidden">
                <button @click="open = !open" class="w-full text-left p-8 focus:outline-none flex justify-between items-center group">
                    <header>
                        <h2 class="text-xl font-black text-gray-900 dark:text-white flex items-center gap-2 group-hover:text-purple-500 transition-colors">
                            <svg class="w-5 h-5 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                            {{ __('Business Details') }}
                        </h2>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                            {{ __("Manage your business-specific information and preferences.") }}
                        </p>
                    </header>
                    <svg class="w-6 h-6 transform transition-transform duration-300 text-gray-400" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>

                <div x-show="open" x-collapse x-cloak class="px-8 pb-8">
                    <div class="max-w-xl">
                        <form method="post" action="{{ route('profile.update') }}" class="space-y-6">
                            @csrf
                            @method('patch')
                            <input type="hidden" name="first_name" value="{{ explode(' ', $user->name)[0] }}">
                            <input type="hidden" name="last_name" value="{{ explode(' ', $user->name)[1] ?? '' }}">

                            <div>
                                <x-label for="business_name" :value="__('Business Name')" />
                                <x-input id="business_name" name="business_name" type="text" class="mt-1 block w-full" :value="old('business_name', $user->business_name)" />
                                <x-input-error class="mt-2" :messages="$errors->get('business_name')" />
                            </div>

                            <div>
                                <x-label for="business_type" :value="__('Business Type')" />
                                <select id="business_type" name="business_type" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 focus:border-blue-500 dark:focus:border-blue-600 focus:ring focus:ring-blue-200 dark:focus:ring-blue-900 rounded-lg shadow-sm">
                                    <option value="General Inventory" {{ $user->business_type == 'General Inventory' ? 'selected' : '' }}>{{ __('General Inventory (Default)') }}</option>
                                    <option value="Gold / Jewellery" {{ $user->business_type == 'Gold / Jewellery' ? 'selected' : '' }}>{{ __('Gold & Jewellery Business') }}</option>
                                    <option value="Electronics" {{ $user->business_type == 'Electronics' ? 'selected' : '' }}>{{ __('Electronics Store') }}</option>
                                    <option value="Grocery" {{ $user->business_type == 'Grocery' ? 'selected' : '' }}>{{ __('Grocery / Supermarket') }}</option>
                                    <option value="Clothing" {{ $user->business_type == 'Clothing' ? 'selected' : '' }}>{{ __('Clothing / Fashion Store') }}</option>
                                    <option value="Medical Store" {{ $user->business_type == 'Medical Store' ? 'selected' : '' }}>{{ __('Medical / Pharmacy Store') }}</option>
                                    <option value="Hardware" {{ $user->business_type == 'Hardware' ? 'selected' : '' }}>{{ __('Hardware / Construction Materials') }}</option>
                                    <option value="Mobile Shop" {{ $user->business_type == 'Mobile Shop' ? 'selected' : '' }}>{{ __('Mobile Phone Shop') }}</option>
                                    <option value="Automobile parts" {{ $user->business_type == 'Automobile parts' ? 'selected' : '' }}>{{ __('Automobile Parts Store') }}</option>
                                    <option value="Furniture" {{ $user->business_type == 'Furniture' ? 'selected' : '' }}>{{ __('Furniture Store') }}</option>
                                    <option value="Cosmetic" {{ $user->business_type == 'Cosmetic' ? 'selected' : '' }}>{{ __('Cosmetic & Beauty Store') }}</option>
                                    <option value="Book Store" {{ $user->business_type == 'Book Store' ? 'selected' : '' }}>{{ __('Book Store / Stationery') }}</option>
                                    <option value="Restaurant" {{ $user->business_type == 'Restaurant' ? 'selected' : '' }}>{{ __('Restaurant / Food Inventory') }}</option>
                                    <option value="Agricultural" {{ $user->business_type == 'Agricultural' ? 'selected' : '' }}>{{ __('Agricultural Products') }}</option>
                                    <option value="Wholesale" {{ $user->business_type == 'Wholesale' ? 'selected' : '' }}>{{ __('Wholesale Distributor') }}</option>
                                </select>
                                <x-input-error class="mt-2" :messages="$errors->get('business_type')" />
                            </div>

                            <div>
                                <x-label for="address" :value="__('Business Address')" />
                                <x-textarea id="address" name="address" class="mt-1 block w-full">{{ old('address', $user->address) }}</x-textarea>
                                <x-input-error class="mt-2" :messages="$errors->get('address')" />
                            </div>

                            <div>
                                <x-label for="currency" :value="__('Currency')" />
                                <select id="currency" name="currency" @change="currency = $event.target.value" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 focus:border-blue-500 dark:focus:border-blue-600 focus:ring focus:ring-blue-200 dark:focus:ring-blue-900 rounded-lg shadow-sm">
                                    <option value="USD" {{ $user->currency == 'USD' ? 'selected' : '' }}>USD ($)</option>
                                    <option value="INR" {{ $user->currency == 'INR' ? 'selected' : '' }}>INR (₹)</option>
                                    <option value="GBP" {{ $user->currency == 'GBP' ? 'selected' : '' }}>GBP (£)</option>
                                    <option value="EUR" {{ $user->currency == 'EUR' ? 'selected' : '' }}>EUR (€)</option>
                                </select>
                                <x-input-error class="mt-2" :messages="$errors->get('currency')" />
                            </div>

                             <div>
                                 <label for="tax_number" class="block text-sm font-medium text-gray-700 dark:text-gray-300" x-text="taxLabel"></label>
                                 <x-input id="tax_number" name="tax_number" type="text" class="mt-1 block w-full" :value="old('tax_number', $user->tax_number)" x-bind:placeholder="taxPlaceholder" />
                                 <x-input-error class="mt-2" :messages="$errors->get('tax_number')" />
                             </div>

                            <div class="flex items-center gap-4 pt-4">
                                <button type="submit" class="bg-gray-900 dark:bg-purple-600 text-white px-8 py-2.5 rounded-xl font-bold hover:bg-black dark:hover:bg-purple-700 transform active:scale-95 transition-all">
                                    {{ __('Save Business Details') }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Online Payment Settings -->
            <div x-data="{
                open: false,
                currency: '{{ $user->currency ?? 'USD' }}',
                get paymentLabel() {
                    const labels = {
                        'INR': '{{ __("UPI ID") }}',
                        'USD': '{{ __("PayPal Email") }}',
                        'GBP': '{{ __("Revolut / Bank ID") }}',
                        'EUR': '{{ __("SEPA / PayPal ID") }}'
                    };
                    return labels[this.currency] || '{{ __("Payment ID") }}';
                },
                get paymentPlaceholder() {
                    const placeholders = {
                        'INR': '{{ __("e.g. yourname@upi") }}',
                        'USD': '{{ __("e.g. you@email.com") }}',
                        'GBP': '{{ __("e.g. @yourrevtag") }}',
                        'EUR': '{{ __("e.g. you@email.com") }}'
                    };
                    return placeholders[this.currency] || '{{ __("Enter your payment ID") }}';
                },
                get paymentHint() {
                    const hints = {
                        'INR': '{{ __("Your UPI ID will be used to generate a payment QR code on invoices. Customers can scan and pay directly.") }}',
                        'USD': '{{ __("Your PayPal email will appear as a QR code on invoices for easy online payments.") }}',
                        'GBP': '{{ __("Your Revolut tag or bank ID will be shown as a QR code on invoices.") }}',
                        'EUR': '{{ __("Your SEPA/PayPal ID will be used to generate a payment QR code on invoices.") }}'
                    };
                    return hints[this.currency] || '{{ __("Your payment ID will be shown on invoices for customer payments.") }}';
                }
            }" class="bg-white dark:bg-gray-900 shadow-2xl rounded-3xl border border-gray-100 dark:border-gray-800 transition-all duration-300 overflow-hidden">
                <button @click="open = !open" class="w-full text-left p-8 focus:outline-none flex justify-between items-center group">
                    <header>
                        <h2 class="text-xl font-black text-gray-900 dark:text-white flex items-center gap-2 group-hover:text-green-500 transition-colors">
                            <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                            {{ __('Online Payment Settings') }}
                        </h2>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                            {{ __("Set up your payment ID for QR code generation on invoices.") }}
                        </p>
                    </header>
                    <svg class="w-6 h-6 transform transition-transform duration-300 text-gray-400" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>

                <div x-show="open" x-collapse x-cloak class="px-8 pb-8">
                    <div class="max-w-xl">
                        <form method="post" action="{{ route('profile.update') }}" class="space-y-6">
                            @csrf
                            @method('patch')
                            <input type="hidden" name="first_name" value="{{ explode(' ', $user->name)[0] }}">
                            <input type="hidden" name="last_name" value="{{ explode(' ', $user->name)[1] ?? '' }}">

                            <!-- Current Currency Display -->
                            <div class="flex items-center gap-3 p-4 bg-gray-50 dark:bg-gray-800/50 rounded-xl border border-gray-200 dark:border-gray-700">
                                <div class="p-2 rounded-lg bg-green-100 dark:bg-green-900/40">
                                    <svg class="w-5 h-5 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('Current Currency') }}</p>
                                    <p class="text-sm font-black text-gray-900 dark:text-white">{{ $user->currency ?? 'USD' }}</p>
                                </div>
                                <div class="ml-auto">
                                    <span class="text-xs text-gray-400 dark:text-gray-500">{{ __('Change in Business Details above') }}</span>
                                </div>
                            </div>

                            <!-- Payment ID Input -->
                            <div>
                                <label for="payment_id" class="block text-sm font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider" x-text="paymentLabel"></label>
                                <input id="payment_id" name="payment_id" type="text"
                                    class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 focus:border-green-500 dark:focus:border-green-600 focus:ring focus:ring-green-200 dark:focus:ring-green-900 rounded-lg shadow-sm"
                                    value="{{ old('payment_id', $user->payment_id) }}"
                                    x-bind:placeholder="paymentPlaceholder" />
                                <p class="mt-2 text-xs text-gray-500 dark:text-gray-400" x-text="paymentHint"></p>
                                <x-input-error class="mt-2" :messages="$errors->get('payment_id')" />
                            </div>

                            <!-- Preview Info -->
                            @if($user->payment_id)
                                <div class="flex items-center gap-3 p-4 bg-green-50 dark:bg-green-900/20 rounded-xl border border-green-200 dark:border-green-800">
                                    <svg class="w-5 h-5 text-green-600 dark:text-green-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <div>
                                        <p class="text-sm font-bold text-green-800 dark:text-green-300">{{ __('Payment QR Active') }}</p>
                                        <p class="text-xs text-green-600 dark:text-green-400">{{ __('A payment QR code will be generated on all your invoices.') }}</p>
                                    </div>
                                </div>
                            @endif

                            <div class="flex items-center gap-4 pt-4">
                                <button type="submit" class="bg-gray-900 dark:bg-green-600 text-white px-8 py-2.5 rounded-xl font-bold hover:bg-black dark:hover:bg-green-700 transform active:scale-95 transition-all">
                                    {{ __('Save Payment Settings') }}
                                </button>

                                @if (session('status') === 'profile-updated')
                                    <p
                                        x-data="{ show: true }"
                                        x-show="show"
                                        x-transition
                                        x-init="setTimeout(() => show = false, 2000)"
                                        class="text-sm text-green-600 dark:text-green-400 font-bold"
                                    >{{ __('Saved.') }}</p>
                                @endif
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Security -->
            <div x-data="{ open: false }" class="bg-white dark:bg-gray-900 shadow-2xl rounded-3xl border border-gray-100 dark:border-gray-800 transition-all duration-300 overflow-hidden">
                <button @click="open = !open" class="w-full text-left p-8 focus:outline-none flex justify-between items-center group">
                    <header>
                        <h2 class="text-xl font-black text-gray-900 dark:text-white flex items-center gap-2 group-hover:text-red-500 transition-colors">
                            <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                            </svg>
                            {{ __('Security') }}
                        </h2>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                            {{ __('Ensure your account is using a long, random password to stay secure.') }}
                        </p>
                    </header>
                    <svg class="w-6 h-6 transform transition-transform duration-300 text-gray-400" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>

                <div x-show="open" x-collapse x-cloak class="px-8 pb-8">
                    <div class="max-w-xl">
                        <form method="post" action="{{ route('profile.password') }}" class="space-y-6">
                            @csrf
                            @method('put')

                            <div>
                                <x-label for="current_password" :value="__('Current Password')" />
                                <x-password-input id="current_password" name="current_password" type="password" class="mt-1 block w-full" autocomplete="current-password" />
                                <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2" />
                            </div>

                            <div>
                                <x-label for="password" :value="__('New Password')" />
                                <x-password-input id="password" name="password" type="password" class="mt-1 block w-full" autocomplete="new-password" />
                                <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2" />
                            </div>

                            <div>
                                <x-label for="password_confirmation" :value="__('Confirm Password')" />
                                <x-password-input id="password_confirmation" name="password_confirmation" type="password" class="mt-1 block w-full" autocomplete="new-password" />
                                <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2" />
                            </div>

                            <div class="flex items-center gap-4 pt-4">
                                <button type="submit" class="bg-gray-900 dark:bg-red-600 text-white px-8 py-2.5 rounded-xl font-bold hover:bg-black dark:hover:bg-red-700 transform active:scale-95 transition-all">
                                    {{ __('Update Password') }}
                                </button>

                                @if (session('status') === 'password-updated')
                                    <p
                                        x-data="{ show: true }"
                                        x-show="show"
                                        x-transition
                                        x-init="setTimeout(() => show = false, 2000)"
                                        class="text-sm text-green-600 dark:text-green-400 font-bold"
                                    >{{ __('Updated.') }}</p>
                                @endif
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Support -->
            <div x-data="{ open: false }" class="bg-blue-50 dark:bg-blue-900/10 shadow-2xl rounded-3xl border border-blue-100 dark:border-blue-900/20 transition-all duration-300 overflow-hidden">
                <button @click="open = !open" class="w-full text-left p-8 focus:outline-none flex justify-between items-center group">
                    <header>
                        <h2 class="text-xl font-black text-blue-900 dark:text-blue-500 flex items-center gap-2 group-hover:text-blue-600 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                            {{ __('Support') }}
                        </h2>
                        <p class="mt-1 text-sm text-blue-600 dark:text-blue-400/60">
                            {{ __('Need help? Contact our support team directly.') }}
                        </p>
                    </header>
                    <svg class="w-6 h-6 transform transition-transform duration-300 text-blue-400" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>

                <div x-show="open" x-collapse x-cloak class="px-8 pb-8">
                    <div class="max-w-xl">
                        <div class="p-6 bg-white dark:bg-gray-800 rounded-2xl border border-blue-100 dark:border-blue-900/40 flex items-center gap-6">
                            <div class="h-16 w-16 rounded-full bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center text-blue-600 dark:text-blue-400">
                                <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-black text-gray-900 dark:text-white uppercase tracking-tight">{{ __('Email Support') }}</h3>
                                <p class="text-sm text-gray-500 dark:text-gray-400 mb-2">{{ __('If you face any problems, feel free to reach out to us at:') }}</p>
                                <a href="mailto:allipatel33@gmail.com" class="text-xl font-black text-blue-600 dark:text-blue-400 hover:underline decoration-2 underline-offset-4">
                                    allipatel33@gmail.com
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Delete Account -->
            <div x-data="{ open: false }" class="bg-red-50 dark:bg-red-900/10 shadow-2xl rounded-3xl border border-red-100 dark:border-red-900/20 transition-all duration-300 overflow-hidden">
                <button @click="open = !open" class="w-full text-left p-8 focus:outline-none flex justify-between items-center group">
                    <header>
                        <h2 class="text-xl font-black text-red-900 dark:text-red-500 flex items-center gap-2 group-hover:text-red-600 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                            {{ __('Account Delete') }}
                        </h2>
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400/60">
                            {{ __('Permanently delete your account and all associated data.') }}
                        </p>
                    </header>
                    <svg class="w-6 h-6 transform transition-transform duration-300 text-red-400" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>

                <div x-show="open" x-collapse x-cloak class="px-8 pb-8">
                    <div class="max-w-xl">
                        <p class="text-sm text-red-600 dark:text-red-400 mb-6 font-medium">
                            {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Before deleting your account, please download any data or information that you wish to retain.') }}
                        </p>

                        <div x-data="{ confirmingUserDeletion: false }">
                            <button @click="confirmingUserDeletion = true" class="bg-red-600 text-white px-8 py-2.5 rounded-xl font-bold hover:bg-red-700 transform active:scale-95 transition-all">
                                {{ __('Delete Account') }}
                            </button>

                            <!-- Delete Confirmation Modal would go here, but for simplicity we can use a small form if Alpine is enabled -->
                            <template x-if="confirmingUserDeletion">
                                <div class="mt-6 p-6 bg-white dark:bg-gray-800 rounded-2xl border border-red-200 dark:border-red-900/40">
                                    <form method="post" action="{{ route('profile.destroy') }}" class="space-y-4">
                                        @csrf
                                        @method('delete')

                                        <h3 class="text-lg font-bold text-gray-900 dark:text-white">
                                            {{ __('Are you sure you want to delete your account?') }}
                                        </h3>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">
                                            {{ __('Please enter your password to confirm you would like to permanently delete your account.') }}
                                        </p>

                                        <div class="max-w-xs">
                                            <x-label for="delete_password" value="{{ __('Password') }}" class="sr-only" />
                                            <x-password-input id="delete_password" name="password" placeholder="{{ __('Password') }}" class="w-full" />
                                            <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-2" />
                                        </div>

                                        <div class="flex gap-4">
                                            <button type="button" @click="confirmingUserDeletion = false" class="text-gray-600 dark:text-gray-400 font-bold hover:underline">
                                                {{ __('Cancel') }}
                                            </button>
                                            <button type="submit" class="bg-red-600 text-white px-6 py-2 rounded-lg font-bold hover:bg-red-700">
                                                {{ __('Confirm Deletion') }}
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
