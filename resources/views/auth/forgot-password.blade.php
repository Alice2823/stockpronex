<x-guest-layout>

    <div class="w-full max-w-md mx-auto bg-white dark:bg-gray-900 shadow-xl rounded-2xl p-8 border border-gray-100 dark:border-gray-800 transition-colors duration-300">

        <!-- Logo -->
        <div class="text-center mb-10">
            <h2 class="text-4xl font-black tracking-tight mt-4">
                <span class="brand-stock dark:text-white">Stock</span><span class="brand-pro text-blue-600 dark:text-blue-500">Pro</span><span class="brand-nex dark:text-gray-400">Nex</span>
            </h2>
            <p class="text-gray-500 dark:text-gray-400 font-medium text-sm mt-2">Inventory Management System</p>
        </div>

        <div class="mb-6 text-sm text-gray-600 dark:text-gray-400 leading-relaxed">
            {{ __('Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.') }}
        </div>

        <!-- Session Status -->
        @if (session('status'))
            <div class="mb-4 p-3 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-xl text-green-700 dark:text-green-400 text-sm font-medium">
                {{ session('status') }}
            </div>
        @endif

        <!-- Errors -->
        @if ($errors->any())
            <div class="mb-4 p-3 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-xl text-red-700 dark:text-red-400 text-sm">
                @foreach ($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('password.email') }}">
            @csrf

            <!-- Email Address -->
            <div>
                <x-label for="email" :value="__('Email')" />
                <x-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus />
            </div>

            <div class="mt-6">
                <button type="submit"
                        class="w-full bg-gray-900 dark:bg-blue-600 text-white py-3 px-4 rounded-xl font-bold hover:bg-black dark:hover:bg-blue-700 transform active:scale-[0.98] transition-all duration-200 shadow-lg shadow-gray-200 dark:shadow-none uppercase tracking-widest text-sm">
                    {{ __('Email Password Reset Link') }}
                </button>
            </div>
        </form>

        <!-- Back to Login -->
        <div class="mt-8 text-center text-sm text-gray-500 dark:text-gray-400">
            Remember your password?
            <a href="{{ route('login') }}" class="text-blue-600 dark:text-blue-400 font-bold hover:underline">
                Log In
            </a>
        </div>

    </div>

</x-guest-layout>
