<x-guest-layout>

    <div class="w-full max-w-md mx-auto bg-white dark:bg-gray-900 shadow-xl rounded-2xl p-8 border border-gray-100 dark:border-gray-800 transition-colors duration-300">

        <!-- Logo -->
        <div class="text-center mb-10">
            <h2 class="text-4xl font-black tracking-tight mt-4">
                <span class="brand-stock dark:text-white">Stock</span><span class="brand-pro text-blue-600 dark:text-blue-500">Pro</span><span class="brand-nex dark:text-gray-400">Nex</span>
            </h2>
            <p class="text-gray-500 dark:text-gray-400 font-medium text-sm mt-2">Reset Your Password</p>
        </div>

        <!-- Errors -->
        @if ($errors->any())
            <div class="mb-4 p-3 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-xl text-red-700 dark:text-red-400 text-sm">
                @foreach ($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('password.update') }}">
            @csrf

            <!-- Password Reset Token -->
            <input type="hidden" name="token" value="{{ $request->route('token') }}">

            <!-- Email Address -->
            <div>
                <x-label for="email" :value="__('Email')" />
                <x-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email', $request->email)" required autofocus />
            </div>

            <!-- Password -->
            <div class="mt-4">
                <x-label for="password" :value="__('Password')" />
                <x-password-input id="password" class="block mt-1 w-full" name="password" required />
            </div>

            <!-- Confirm Password -->
            <div class="mt-4">
                <x-label for="password_confirmation" :value="__('Confirm Password')" />
                <x-password-input id="password_confirmation" class="block mt-1 w-full" name="password_confirmation" required />
            </div>

            <div class="mt-6">
                <button type="submit"
                        class="w-full bg-gray-900 dark:bg-blue-600 text-white py-3 px-4 rounded-xl font-bold hover:bg-black dark:hover:bg-blue-700 transform active:scale-[0.98] transition-all duration-200 shadow-lg shadow-gray-200 dark:shadow-none uppercase tracking-widest text-sm">
                    {{ __('Reset Password') }}
                </button>
            </div>
        </form>

    </div>

</x-guest-layout>
