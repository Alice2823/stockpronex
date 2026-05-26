{{-- Standard Login Form (email + password) --}}
<form method="POST" action="{{ route('login') }}">
    @csrf

    <!-- Email -->
    <div>
        <x-label for="email" :value="__('Email')" />
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
                {{ __('Remember me') }}
            </span>
        </label>

        <!-- Forgot Password -->
        <a href="{{ route('password.request') }}"
           class="text-sm text-blue-600 dark:text-blue-400 font-semibold hover:text-blue-700 dark:hover:text-blue-300 transition-colors">
            {{ __('Forgot Password?') }}
        </a>

    </div>

    <!-- Turnstile CAPTCHA (rendered by global onTurnstileReady callback) -->
    <div class="mt-6 mb-2 flex justify-center">
        <div id="turnstile-full"></div>
    </div>

    <!-- Login Button -->
    <div class="mt-4">
        <button type="submit"
                class="w-full bg-gray-900 dark:bg-blue-600 text-white py-3 px-4 rounded-xl font-bold hover:bg-black dark:hover:bg-blue-700 transform active:scale-[0.98] transition-all duration-200 shadow-lg shadow-gray-200 dark:shadow-none">
            {{ __('Log In') }}
        </button>
    </div>

</form>
