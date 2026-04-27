<x-guest-layout>


    <div
        class="w-full max-w-md mx-auto bg-white dark:bg-gray-900 shadow-xl rounded-2xl p-8 border border-gray-100 dark:border-gray-800 transition-colors duration-300">

        <!-- Logo Animation Styles -->
        <style>
            .logo-expand-container {
                display: flex;
                justify-content: center;
                align-items: baseline;
                flex-direction: row;
            }

            .brand-stock,
            .brand-pro,
            .brand-nex {
                display: inline-flex;
                align-items: baseline;
                flex-direction: row;
            }

            .logo-letter {
                display: inline-block;
            }

            .logo-rest {
                display: inline-block;
                max-width: 0;
                opacity: 0;
                overflow: hidden;
                white-space: nowrap;
                padding-right: 0.15em;
                margin-right: -0.15em;
                animation: expandWord 1.4s cubic-bezier(0.16, 1, 0.3, 1) forwards;
                animation-delay: 0.8s;
            }

            @keyframes expandWord {
                0% {
                    max-width: 0;
                    opacity: 0;
                }

                40% {
                    opacity: 0;
                }

                100% {
                    max-width: 150px;
                    opacity: 1;
                }
            }

            .subtitle-fade {
                opacity: 0;
                animation: fadeIn 0.8s ease forwards 2s;
            }

            @keyframes fadeIn {
                0% {
                    opacity: 0;
                    transform: translateY(8px);
                }

                100% {
                    opacity: 1;
                    transform: translateY(0);
                }
            }

            /* Recent Login Styles */
            .recent-login-card {
                position: relative;
                cursor: pointer;
                transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
                border: 2px solid transparent;
            }

            .recent-login-card:hover {
                border-color: #3b82f6;
                transform: translateY(-2px);
                box-shadow: 0 8px 25px -5px rgba(59, 130, 246, 0.15);
            }

            .recent-login-card.selected {
                border-color: #3b82f6;
                background: rgba(59, 130, 246, 0.05);
            }

            .dark .recent-login-card:hover {
                box-shadow: 0 8px 25px -5px rgba(59, 130, 246, 0.25);
            }

            .dark .recent-login-card.selected {
                background: rgba(59, 130, 246, 0.1);
            }

            .recent-login-remove {
                position: absolute;
                top: -6px;
                right: -6px;
                width: 20px;
                height: 20px;
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                opacity: 0;
                transition: opacity 0.2s;
                z-index: 10;
            }

            .recent-login-card:hover .recent-login-remove {
                opacity: 1;
            }

            .avatar-gradient-1 {
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            }

            .avatar-gradient-2 {
                background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            }

            .avatar-gradient-3 {
                background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            }

            .avatar-gradient-4 {
                background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
            }

            .avatar-gradient-5 {
                background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);
            }

            .slide-down-enter {
                animation: slideDown 0.3s cubic-bezier(0.4, 0, 0.2, 1) forwards;
            }

            @keyframes slideDown {
                0% {
                    opacity: 0;
                    max-height: 0;
                    transform: translateY(-10px);
                }

                100% {
                    opacity: 1;
                    max-height: 200px;
                    transform: translateY(0);
                }
            }

            .slide-up-enter {
                animation: slideUp 0.3s cubic-bezier(0.4, 0, 0.2, 1) forwards;
            }

            @keyframes slideUp {
                0% {
                    opacity: 0;
                    max-height: 0;
                }

                100% {
                    opacity: 1;
                    max-height: 500px;
                }
            }
        </style>

        <!-- Logo -->
        <div class="text-center mb-8 text-4xl font-black tracking-tight mt-4 italic">
            <h2 class="logo-expand-container">
                <span class="brand-stock dark:text-white">
                    <span class="logo-letter">S</span><span class="logo-rest">tock</span>
                </span>
                <span class="brand-pro text-blue-600 dark:text-blue-500">
                    <span class="logo-letter">P</span><span class="logo-rest">ro</span>
                </span>
                <span class="brand-nex text-gray-400 dark:text-gray-400">
                    <span class="logo-letter">N</span><span class="logo-rest">ex</span>
                </span>
            </h2>
            <p class="text-gray-500 dark:text-gray-400 font-medium text-sm mt-2 subtitle-fade not-italic">Inventory
                Management System</p>
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

        {{-- ========== LOGIN SECTION ========== --}}
        @if(!empty($recentLogins) && count($recentLogins) > 0)
            <div x-data="{
                selectedAccount: null,
                showFullForm: false,
                recentLogins: {{ json_encode($recentLogins) }},
                
                init() {
                    // Turnstile auto-renders natively.
                },

                selectAccount(login) {
                    this.selectedAccount = login;
                    this.showFullForm = false;
                    this.$nextTick(() => {
                        const pwdInput = document.getElementById('recent-password');
                        if (pwdInput) pwdInput.focus();
                    });
                },

                clearSelection() {
                    this.selectedAccount = null;
                    this.showFullForm = true;
                    this.$nextTick(() => {
                        const emailInput = document.getElementById('email');
                        if (emailInput) emailInput.focus();
                    });
                },

                async removeAccount(email, index, event) {
                    event.stopPropagation();
                    try {
                        await fetch('{{ route('recent-login.remove') }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({ email: email })
                        });
                        this.recentLogins.splice(index, 1);
                        if (this.selectedAccount && this.selectedAccount.email === email) {
                            this.selectedAccount = null;
                        }
                    } catch(e) {
                        console.error('Failed to remove account', e);
                    }
                }
            }" x-cloak>

                {{-- Recent Logins Header --}}
                <div x-show="!showFullForm">
                    <div class="flex items-center justify-between mb-3">
                        <h3 class="text-sm font-bold text-gray-500 dark:text-gray-400 uppercase tracking-widest">Recent Logins</h3>
                        <span class="text-xs text-gray-400 dark:text-gray-500">Click to quick login</span>
                    </div>

                    {{-- Account Cards --}}
                    <div class="space-y-2 mb-4">
                        <template x-for="(login, index) in recentLogins" :key="login.email">
                            <div class="recent-login-card rounded-xl p-3 bg-gray-50 dark:bg-gray-800/60"
                                 :class="{ 'selected': selectedAccount && selectedAccount.email === login.email }"
                                 @click="selectAccount(login)">

                                {{-- Remove Button --}}
                                <button class="recent-login-remove bg-red-500 hover:bg-red-600 text-white"
                                        @click="removeAccount(login.email, index, $event)"
                                        title="Remove">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                </button>

                                <div class="flex items-center">
                                    {{-- Avatar --}}
                                    <div class="flex-shrink-0 w-11 h-11 rounded-full flex items-center justify-center text-white font-bold text-lg shadow-md"
                                         :class="'avatar-gradient-' + ((index % 5) + 1)">
                                        <span x-text="login.initial"></span>
                                    </div>

                                    {{-- Info --}}
                                    <div class="ml-3 flex-1 min-w-0">
                                        <p class="text-sm font-bold text-gray-900 dark:text-white truncate" x-text="login.name"></p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400 truncate" x-text="login.email"></p>
                                        <p x-show="login.business_name" class="text-[10px] font-semibold text-blue-600 dark:text-blue-400 truncate" x-text="login.business_name"></p>
                                    </div>

                                    {{-- Arrow / Check --}}
                                    <div class="flex-shrink-0 ml-2">
                                        <svg x-show="!(selectedAccount && selectedAccount.email === login.email)" class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                        </svg>
                                        <svg x-show="selectedAccount && selectedAccount.email === login.email" class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                                        </svg>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>

                    {{-- Password form for selected account --}}
                    <div x-show="selectedAccount" x-transition class="slide-down-enter mb-4">
                        <form method="POST" action="{{ route('login') }}">
                            @csrf
                            <input type="hidden" name="email" :value="selectedAccount ? selectedAccount.email : ''">

                            <div class="relative">
                                <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1">
                                    Password
                                </label>
                                <x-password-input id="recent-password" class="block w-full"
                                                name="password"
                                                required autocomplete="current-password" />
                            </div>

                            <!-- Turnstile CAPTCHA -->
                            <div class="mt-4 flex justify-center">
                                <div class="cf-turnstile" data-sitekey="{{ config('services.turnstile.site_key') }}" data-theme="auto"></div>
                            </div>

                            {{-- Remember + Login --}}
                            <div class="flex items-center justify-between mt-3">
                                <label class="flex items-center cursor-pointer group">
                                    <input type="checkbox"
                                           name="remember"
                                           class="rounded border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:checked:bg-blue-600 transition-colors">
                                    <span class="ml-2 text-sm text-gray-600 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-gray-200 transition-colors">
                                        Remember me
                                    </span>
                                </label>
                                <a href="{{ route('password.request') }}"
                                   class="text-xs text-blue-600 dark:text-blue-400 font-semibold hover:text-blue-700 dark:hover:text-blue-300 transition-colors">
                                    Forgot?
                                </a>
                            </div>

                            <button type="submit"
                                    class="mt-4 w-full bg-gray-900 dark:bg-blue-600 text-white py-3 px-4 rounded-xl font-bold hover:bg-black dark:hover:bg-blue-700 transform active:scale-[0.98] transition-all duration-200 shadow-lg shadow-gray-200 dark:shadow-none">
                                LOG IN
                            </button>
                        </form>
                    </div>

                    {{-- Use Another Account --}}
                    <button @click="clearSelection()"
                            class="w-full mt-3 flex items-center justify-center gap-2 py-2.5 rounded-xl border-2 border-dashed border-gray-200 dark:border-gray-700 text-sm font-semibold text-gray-500 dark:text-gray-400 hover:border-blue-400 hover:text-blue-600 dark:hover:border-blue-500 dark:hover:text-blue-400 transition-all duration-200">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        Use another account
                    </button>
                </div>

                {{-- Full Login Form --}}
                <div x-show="showFullForm" x-transition class="slide-up-enter">
                    {{-- Back button --}}
                    <button @click="showFullForm = false; selectedAccount = null;"
                            class="mb-4 flex items-center gap-1 text-sm font-semibold text-gray-500 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                        </svg>
                        Back to recent logins
                    </button>

                    @include('auth._login_form')
                </div>
            </div>
        @else
            {{-- No Recent Logins --}}
            @include('auth._login_form')
        @endif

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
    </div> {{-- End of the Box --}}

    <!-- Turnstile API implicitly renders elements with .cf-turnstile -->
    <script src="https://challenges.cloudflare.com/turnstile/v0/api.js" async defer></script>
</x-guest-layout>