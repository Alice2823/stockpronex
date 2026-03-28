<x-app-layout>
    <x-slot name="header">
        <h2 class="font-extrabold text-3xl text-gray-900 dark:text-white leading-tight">
            {{ __('Choose Your Plan') }}
        </h2>
    </x-slot>

    <div class="py-16 bg-gray-50 dark:bg-gray-950 min-h-screen" x-data="{ billingCycle: 'monthly' }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            @if(config('app.developer_mode'))
                <div class="mb-8 p-4 bg-green-100 dark:bg-green-900/30 border border-green-200 dark:border-green-800 rounded-2xl text-center">
                    <p class="text-green-800 dark:text-green-400 font-bold">
                        🚀 <span class="uppercase tracking-widest">Developer Mode Active:</span> All features are currently unlocked for testing.
                    </p>
                </div>
            @endif

            <!-- Header Section -->
            <div class="text-center mb-16">
                <h1 class="text-4xl md:text-6xl font-black text-gray-900 dark:text-white tracking-tight mb-4">
                    {{ __('Elevate Your Business') }}
                </h1>
                <p class="text-lg text-gray-600 dark:text-gray-400 max-w-2xl mx-auto">
                    {{ __('Scale your stock management with our flexible tiers. Choose the plan that fits your growth.') }}
                </p>

                <!-- Billing Toggle -->
                <div class="mt-10 flex items-center justify-center space-x-4">
                    <span class="text-sm font-bold" :class="billingCycle === 'monthly' ? 'text-gray-900 dark:text-white' : 'text-gray-500'">{{ __('Monthly') }}</span>
                    <button @click="billingCycle = billingCycle === 'monthly' ? 'yearly' : 'monthly'" 
                            class="relative w-16 h-8 rounded-full bg-blue-600 transition-colors duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                        <div class="absolute top-1 left-1 w-6 h-6 rounded-full bg-white transition-transform duration-300 shadow-md"
                             :class="billingCycle === 'yearly' ? 'translate-x-8' : ''"></div>
                    </button>
                    <span class="text-sm font-bold" :class="billingCycle === 'yearly' ? 'text-gray-900 dark:text-white' : 'text-gray-500'">{{ __('Yearly') }} <span class="text-green-500 ml-1">{{ __('(Save ~10%)') }}</span></span>
                </div>
            </div>

            <!-- Pricing Grid -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 items-stretch">
                
                <!-- Free Plan -->
                <div class="bg-white dark:bg-gray-900 rounded-3xl p-8 border border-gray-100 dark:border-gray-800 shadow-xl flex flex-col relative overflow-hidden">
                    <div class="mb-8">
                        <h3 class="text-xl font-black text-gray-900 dark:text-white uppercase tracking-widest mb-2">{{ __('Free') }}</h3>
                        <div class="text-4xl font-black text-gray-900 dark:text-white">₹0</div>
                        <p class="text-gray-500 mt-2">{{ __('Perfect for getting started') }}</p>
                    </div>

                    <ul class="space-y-4 mb-8 flex-grow">
                        <li class="flex items-center text-gray-700 dark:text-gray-300">
                            <svg class="h-5 w-5 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            {{ __('Up to 3 Stock Entries') }}
                        </li>
                        <li class="flex items-center text-gray-400 line-through decoration-gray-500/50">
                            <svg class="h-5 w-5 text-gray-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            {{ __('Advanced Analytics') }}
                        </li>
                        <li class="flex items-center text-gray-400 line-through decoration-gray-500/50">
                            <svg class="h-5 w-5 text-gray-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            {{ __('Payment Received Dashboard') }}
                        </li>
                             <svg class="h-5 w-5 text-gray-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            {{ __('Dark/Light Mode Toggle') }}
                        </li>
                        <li class="flex items-center text-gray-400 line-through decoration-gray-500/50">
                            <svg class="h-5 w-5 text-gray-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            {{ __('Barcode Scanning') }}
                        </li>
                    </ul>

                    @if(Auth::user()->plan === 'free')
                        <button disabled class="w-full py-4 px-6 rounded-2xl bg-gray-100 dark:bg-gray-800 text-gray-500 font-bold uppercase tracking-widest cursor-not-allowed">
                            {{ __('Current Plan') }}
                        </button>
                    @else
                        <a href="{{ route('dashboard') }}" class="w-full py-4 px-6 rounded-2xl bg-gray-100 dark:bg-gray-800 text-gray-900 dark:text-white text-center font-bold uppercase tracking-widest hover:bg-gray-200 dark:hover:bg-gray-700 transition-colors">
                            {{ __('Active') }}
                        </a>
                    @endif
                </div>

                <!-- Standard Plan -->
                <div class="bg-white dark:bg-gray-900 rounded-3xl p-8 border-2 border-blue-500 shadow-2xl flex flex-col relative overflow-hidden transform md:-translate-y-4">
                    <div class="absolute top-0 right-0 bg-blue-500 text-white text-[10px] font-black uppercase tracking-widest py-1 px-4 rounded-bl-xl">{{ __('Popular') }}</div>
                    <div class="mb-8">
                        <h3 class="text-xl font-black text-gray-900 dark:text-white uppercase tracking-widest mb-2">{{ __('Standard') }}</h3>
                        <div x-show="billingCycle === 'monthly'">
                            <span class="text-4xl font-black text-gray-900 dark:text-white">
                               ₹{{ \App\Constants\Plan::PRICES['standard']['monthly'] }}
                             </span>
                            <span class="text-gray-500">/mo</span>
                        </div>
                        <div x-show="billingCycle === 'yearly'">
                            <span class="text-4xl font-black text-gray-900 dark:text-white">
                               ₹{{ \App\Constants\Plan::PRICES['standard']['yearly'] }}
                            </span>
                            <span class="text-gray-500">/yr</span>
                        </div>
                        <p class="text-gray-500 mt-2">{{ __('Advanced features for growing businesses') }}</p>
                    </div>

                    <ul class="space-y-4 mb-8 flex-grow">
                        <li class="flex items-center text-gray-700 dark:text-gray-300 font-bold">
                            <svg class="h-5 w-5 text-blue-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            {{ __('Up to 20 Stock Entries') }}
                        </li>
                        <li class="flex items-center text-gray-700 dark:text-gray-300">
                            <svg class="h-5 w-5 text-blue-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            {{ __('Dark/Light Mode Access') }}
                        </li>
                        <li class="flex items-center text-gray-700 dark:text-gray-300">
                            <svg class="h-5 w-5 text-blue-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            {{ __('Payment Received Dashboard') }}
                        </li>
                        <li class="flex items-center text-gray-400 line-through decoration-gray-500/50">
                            <svg class="h-5 w-5 text-gray-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            Advanced Analytics
                        </li>
                    </ul>

                    <div x-show="billingCycle === 'monthly'">
                        @include('subscription.partials.razorpay-form', ['plan' => 'standard', 'cycle' => 'monthly', 'amount' => 199 * 100, 'orderId' => $orders['standard']['monthly'] ?? null])
                    </div>
                    <div x-show="billingCycle === 'yearly'">
                        @include('subscription.partials.razorpay-form', ['plan' => 'standard', 'cycle' => 'yearly', 'amount' => 999 * 100, 'orderId' => $orders['standard']['yearly'] ?? null])
                    </div>
                </div>

                <!-- Pro Plan -->
                <div class="bg-white dark:bg-gray-900 rounded-3xl p-8 border border-gray-100 dark:border-gray-800 shadow-xl flex flex-col relative overflow-hidden">
                    <div class="mb-8">
                        <h3 class="text-xl font-black text-gray-900 dark:text-white uppercase tracking-widest mb-2">{{ __('Pro') }}</h3>
                        <div x-show="billingCycle === 'monthly'">
                            <span class="text-4xl font-black text-gray-900 dark:text-white">
                                ₹{{ \App\Constants\Plan::PRICES['pro']['monthly'] }}
                            </span>
                            <span class="text-gray-500">/mo</span>
                        </div>
                        <div x-show="billingCycle === 'yearly'">
                            <span class="text-4xl font-black text-gray-900 dark:text-white">
                                ₹{{ \App\Constants\Plan::PRICES['pro']['yearly'] }}
                            </span>
                            <span class="text-gray-500">/yr</span>
                        </div>
                        <p class="text-gray-500 mt-2">{{ __('Everything you need for full control') }}</p>
                    </div>

                    <ul class="space-y-4 mb-8 flex-grow">
                        <li class="flex items-center text-gray-700 dark:text-gray-300 font-bold">
                            <svg class="h-5 w-5 text-purple-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            {{ __('Unlimited Stock Entries') }}
                        </li>
                        <li class="flex items-center text-gray-700 dark:text-gray-300 font-bold">
                            <svg class="h-5 w-5 text-purple-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            {{ __('Advanced Analytics') }}
                        </li>
                        <li class="flex items-center text-gray-700 dark:text-gray-300">
                            <svg class="h-5 w-5 text-purple-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            {{ __('Full Search & Exports') }}
                        </li>
                        <li class="flex items-center text-gray-700 dark:text-gray-300">
                            <svg class="h-5 w-5 text-purple-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            {{ __('Barcode Scanning') }}
                        </li>
                        <li class="flex items-center text-gray-700 dark:text-gray-300">
                            <svg class="h-5 w-5 text-purple-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            {{ __('Priority Support') }}
                        </li>
                    </ul>

                    <div x-show="billingCycle === 'monthly'">
                        @include('subscription.partials.razorpay-form', ['plan' => 'pro', 'cycle' => 'monthly', 'amount' => 199 * 100, 'orderId' => $orders['pro']['monthly'] ?? null])
                    </div>
                    <div x-show="billingCycle === 'yearly'">
                        @include('subscription.partials.razorpay-form', ['plan' => 'pro', 'cycle' => 'yearly', 'amount' => 999 * 100, 'orderId' => $orders['pro']['yearly'] ?? null])
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- Global Styles for Razorpay Buttons -->
    <style>
        .razorpay-payment-button {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 1rem 1.5rem;
            border: none;
            font-size: 0.875rem;
            font-weight: 800;
            border-radius: 1rem;
            color: #ffffff;
            background-color: #2563EB;
            box-shadow: 0 10px 15px -3px rgba(37, 99, 235, 0.4);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            cursor: pointer;
            width: 100%;
            text-transform: uppercase;
            letter-spacing: 0.1em;
        }

        .razorpay-payment-button:hover {
            background-color: #1D4ED8;
            transform: translateY(-2px);
            box-shadow: 0 20px 25px -5px rgba(37, 99, 235, 0.5);
        }
        
        [x-cloak] { display: none !important; }
    </style>
</x-app-layout>