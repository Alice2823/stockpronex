<x-guest-layout>

    <div class="w-full max-w-md mx-auto bg-white dark:bg-gray-900 shadow-xl rounded-2xl p-8 border border-gray-100 dark:border-gray-800 transition-colors duration-300">

        <!-- Logo -->
        <div class="text-center mb-10">
            <h2 class="text-4xl font-black tracking-tight mt-4">
                <span class="brand-stock dark:text-white">Stock</span><span class="brand-pro text-blue-600 dark:text-blue-500">Pro</span><span class="brand-nex dark:text-gray-400">Nex</span>
            </h2>
            <p class="text-gray-500 dark:text-gray-400 font-medium text-sm mt-2">Verify Your Email</p>
        </div>

        <div class="mb-6 text-sm text-gray-600 dark:text-gray-400 leading-relaxed">
            {{ __('Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed to you? If you didn\'t receive the email, we will gladly send you another.') }}
        </div>

        @if (session('status') == 'verification-link-sent')
            <div class="mb-4 p-3 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-xl text-green-700 dark:text-green-400 text-sm font-medium">
                {{ __('A new verification link has been sent to the email address you provided during registration.') }}
            </div>
        @endif

        <div class="mt-6 flex items-center justify-between">
            <form method="POST" action="{{ route('verification.send') }}">
                @csrf
                <button type="submit"
                        class="bg-gray-900 dark:bg-blue-600 text-white py-3 px-6 rounded-xl font-bold hover:bg-black dark:hover:bg-blue-700 transform active:scale-[0.98] transition-all duration-200 shadow-lg shadow-gray-200 dark:shadow-none uppercase tracking-widest text-xs">
                    {{ __('Resend Verification Email') }}
                </button>
            </form>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="text-sm text-gray-500 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white font-semibold transition-colors">
                    {{ __('Log Out') }}
                </button>
            </form>
        </div>

    </div>

</x-guest-layout>
