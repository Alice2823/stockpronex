<x-guest-layout>

    <div class="w-full max-w-md mx-auto bg-white dark:bg-gray-900 shadow-xl rounded-2xl p-8 border border-gray-100 dark:border-gray-800 transition-colors duration-300">

        <!-- Logo -->
        <div class="text-center mb-10">
            <h2 class="text-4xl font-black tracking-tight mt-4">
                <span class="brand-stock">Stock</span><span class="brand-pro">Pro</span><span class="brand-nex">Nex</span>
            </h2>
            <p class="text-gray-500 dark:text-gray-400 font-medium text-sm mt-2">Inventory Management System</p>
        </div>

        <!-- Session Status -->
        @if (session('status'))
            <div class="mb-4 text-green-600 text-sm">
                {{ session('status') }}
            </div>
        @endif

        <!-- Errors -->
        @if ($errors->any())
            <div class="mb-4 text-red-600 text-sm">
                @foreach ($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        <!-- Login Form -->
        <form method="POST" action="{{ route('login') }}">
            @csrf

            <!-- Email -->
            <div>
                <x-label for="email" value="Email" />
                <x-input id="email" type="email" name="email" :value="old('email')" required autofocus class="block mt-1 w-full" />
            </div>

            <!-- Password -->
            <div class="mt-4">
                <x-label for="password" :value="__('Password')" />

                <x-password-input id="password" class="block mt-1 w-full"
                                name="password"
                                required autocomplete="current-password" />
            </div>

            <!-- Remember + Forgot -->
            <div class="flex items-center justify-between mt-4">

                <label class="flex items-center cursor-pointer group">
                    <input type="checkbox"
                           name="remember"
                           class="rounded border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:checked:bg-blue-600 transition-colors">
                    <span class="ml-2 text-sm text-gray-600 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-gray-200 transition-colors">
                        Remember me
                    </span>
                </label>

                <!-- Forgot Password -->
                <a href="{{ route('password.request') }}"
                   class="text-sm text-blue-600 dark:text-blue-400 font-semibold hover:text-blue-700 dark:hover:text-blue-300 transition-colors">
                    Forgot Password?
                </a>

            </div>

            <!-- Login Button -->
            <div class="mt-6">
                <button type="submit"
                        class="w-full bg-gray-900 dark:bg-blue-600 text-white py-3 px-4 rounded-xl font-bold hover:bg-black dark:hover:bg-blue-700 transform active:scale-[0.98] transition-all duration-200 shadow-lg shadow-gray-200 dark:shadow-none">
                    LOG IN
                </button>
            </div>

        </form>

        <!-- Google Login -->
        <div class="mt-8 text-center text-xs font-bold text-gray-400 dark:text-gray-500 uppercase tracking-widest">
            Or continue with
        </div>

        <a href="{{ route('social.redirect', 'google') }}"
           class="mt-4 w-full flex items-center justify-center gap-3 border border-gray-200 dark:border-gray-700 py-3 rounded-xl font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors">
            <svg class="w-5 h-5" viewBox="0 0 24 24">
                <path fill="currentColor" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/>
                <path fill="currentColor" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
                <path fill="currentColor" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l3.66-2.84z" fill="#FBBC05"/>
                <path fill="currentColor" d="M12 5.38c1.62 0 3.06.56 4.21 1.66l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/>
            </svg>
            Sign in with Google
        </a>

        <!-- Register -->
        <div class="mt-8 text-center text-sm text-gray-500 dark:text-gray-400">
            Don't have account?
            <a href="{{ route('register') }}" class="text-blue-600 dark:text-blue-400 font-bold hover:underline">
                Register
            </a>
        </div>

    </div>

</x-guest-layout>