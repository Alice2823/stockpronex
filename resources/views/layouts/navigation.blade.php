<nav x-data="{ open: false }" class="bg-white dark:bg-gray-900 border-b border-gray-100 dark:border-gray-800">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-24">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}" class="flex items-center group transition-all duration-300">
                        <span class="text-3xl font-black tracking-tighter uppercase italic leading-none">
                            <span class="brand-stock dark:text-white">Stock</span><span class="brand-pro text-blue-600 dark:text-blue-500">Pro</span><span class="brand-nex dark:text-gray-400">Nex</span>
                        </span>
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        {{ __('Dashboard') }}
                    </x-nav-link>
                    <x-nav-link :href="route('usage.index')" :active="request()->routeIs('usage.*')">
                        {{ __('Stock Usage') }}
                    </x-nav-link>
                    <x-nav-link :href="Auth::user()->hasFeature('analytics') ? route('dashboard.analytics') : route('subscription.index')" :active="request()->routeIs('dashboard.analytics')">
                        <div class="flex items-center">
                            {{ __('Analytics') }}
                            @if(!Auth::user()->hasFeature('analytics'))
                                <svg class="h-3 w-3 ml-1 text-gray-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd" /></svg>
                            @endif
                        </div>
                    </x-nav-link>
                    <x-nav-link :href="Auth::user()->hasFeature('payments') ? route('dashboard.payments') : route('subscription.index')" :active="request()->routeIs('dashboard.payments')">
                        <div class="flex items-center">
                            {{ __('Payment Received') }}
                            @if(!Auth::user()->hasFeature('payments'))
                                <svg class="h-3 w-3 ml-1 text-gray-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd" /></svg>
                            @endif
                        </div>
                    </x-nav-link>


                    @if(Auth::user()->is_subscribed)
                        <div class="inline-flex items-center px-4 py-1.5 mt-4 sm:mt-0 font-bold text-xs premium-badge shadow-sm transition-all duration-300">
                            <span class="mr-1.5 text-blue-500">⭐</span>
                            {{ __('Premium') }}
                        </div>
                    @else
                        <div class="flex items-center mt-4 sm:ml-4 sm:mt-0">
                            <a href="{{ route('subscription.index') }}" 
                               class="nav-upgrade-btn inline-flex items-center justify-center text-sm">
                                <span class="mr-1.5 text-yellow-300">⚡</span>
                                {{ __('Upgrade') }}
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Settings Area: Theme Toggle + Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ml-6 sm:space-x-4">
                <!-- Animated Theme Toggle (Standalone in navbar) -->
                <div x-data="{
                    dark: localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches),
                    hasAccess: {{ Auth::user()->hasFeature('dark_mode') ? 'true' : 'false' }},
                    toggle() {
                        if (!this.hasAccess) {
                            window.location.href = '{{ route('subscription.index') }}';
                            return;
                        }
                        this.dark = !this.dark;
                        if (this.dark) {
                            document.documentElement.classList.add('dark');
                            localStorage.theme = 'dark';
                        } else {
                            document.documentElement.classList.remove('dark');
                            localStorage.theme = 'light';
                        }
                    }
                }" class="relative">
                    <button @click="toggle()" 
                            :class="dark ? 'theme-toggle theme-toggle--dark' : 'theme-toggle theme-toggle--light'"
                            :aria-label="dark ? 'Switch to light mode' : 'Switch to dark mode'"
                            title="Toggle theme">
                        
                        @if(!Auth::user()->hasFeature('dark_mode'))
                            <div class="absolute -top-1 -right-1 z-10 bg-yellow-400 rounded-full p-0.5 shadow-sm border border-white">
                                <svg class="h-2 w-2 text-white" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd" /></svg>
                            </div>
                        @endif

                        <svg class="theme-toggle__stars" width="20" height="16" viewBox="0 0 20 16">
                            <circle class="theme-toggle__star" cx="4" cy="4" r="1.5"/>
                            <circle class="theme-toggle__star" cx="12" cy="2" r="1"/>
                            <circle class="theme-toggle__star" cx="8" cy="10" r="1.2"/>
                        </svg>
                        <svg class="theme-toggle__clouds" width="20" height="12" viewBox="0 0 20 12">
                            <circle cx="5" cy="8" r="4" fill="white" opacity="0.6"/>
                            <circle cx="10" cy="6" r="5" fill="white" opacity="0.5"/>
                            <circle cx="15" cy="8" r="3.5" fill="white" opacity="0.4"/>
                        </svg>
                        <div class="theme-toggle__thumb">
                            <svg class="theme-toggle__sun" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2.5" stroke-linecap="round">
                                <circle cx="12" cy="12" r="4"/>
                                <path d="M12 2v2M12 20v2M4.93 4.93l1.41 1.41M17.66 17.66l1.41 1.41M2 12h2M20 12h2M4.93 19.07l1.41-1.41M17.66 6.34l1.41-1.41"/>
                            </svg>
                            <svg class="theme-toggle__moon" viewBox="0 0 24 24" fill="none" stroke="#334155" stroke-width="2.5" stroke-linecap="round">
                                <path d="M21 12.79A9 9 0 1111.21 3 7 7 0 0021 12.79z"/>
                            </svg>
                        </div>
                    </button>
                </div>

                <!-- Profile Dropdown -->
                <x-dropdown align="right" width="60">
                    <x-slot name="trigger">
                        <button class="flex items-center px-3 py-2 border border-gray-200 dark:border-gray-700 rounded-full hover:bg-gray-50 dark:hover:bg-gray-800 hover:border-blue-300 transition-all duration-300 focus:outline-none">
                            <div class="flex items-center space-x-2">
                                <div class="h-8 w-8 rounded-full bg-blue-100 dark:bg-blue-900 flex items-center justify-center text-blue-600 dark:text-blue-400">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                </div>
                                <div class="text-sm font-bold text-gray-700 dark:text-gray-300 uppercase tracking-tight">{{ Auth::user()->name }}</div>
                                <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <div class="px-4 py-3 bg-gray-50/50 dark:bg-gray-900/50 border-b border-gray-100 dark:border-gray-700/50">
                            <p class="text-[10px] text-gray-500 dark:text-gray-500 font-black uppercase tracking-widest mb-0.5">Signed in as</p>
                            <p class="text-sm font-bold text-gray-900 dark:text-white truncate">{{ Auth::user()->email }}</p>
                        </div>

                        <!-- Settings -->
                        <x-dropdown-link :href="route('profile.edit')" class="dark:text-gray-300 font-bold hover:bg-blue-50 dark:hover:bg-blue-900/20">
                            <div class="flex items-center">
                                <svg class="h-4 w-4 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                {{ __('Account Settings') }}
                            </div>
                        </x-dropdown-link>

                        <!-- Language Switcher -->
                        <div x-data="{ open: false }" class="border-b border-gray-100 dark:border-gray-700/50">
                            <button @click.stop="open = !open" class="w-full flex items-center justify-between px-4 py-3 text-sm font-bold text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors duration-200">
                                <div class="flex items-center">
                                    <svg class="h-4 w-4 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5c-.356 2.446-1.276 4.706-2.703 6.643M6.412 9a11.97 11.97 0 002.828 4.605m0 0a12.001 12.001 0 01-3.262 3.064m3.262-3.064l-.505.5" />
                                    </svg>
                                    {{ __('Language') }}
                                </div>
                                <svg class="h-4 w-4 transform transition-transform duration-200" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>
                            <div x-show="open" x-collapse class="bg-gray-50 dark:bg-gray-800/50 py-1">
                                <form method="POST" action="{{ route('profile.update') }}">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" name="language" value="en" class="w-full text-left px-8 py-2 text-xs font-bold {{ App::getLocale() == 'en' ? 'text-blue-600 dark:text-blue-400 bg-blue-50/50 dark:bg-blue-900/20' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700/50' }}">
                                        English
                                    </button>
                                    <button type="submit" name="language" value="hi" class="w-full text-left px-8 py-2 text-xs font-bold {{ App::getLocale() == 'hi' ? 'text-blue-600 dark:text-blue-400 bg-blue-50/50 dark:bg-blue-900/20' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700/50' }}">
                                        Hindi
                                    </button>
                                    <button type="submit" name="language" value="gu" class="w-full text-left px-8 py-2 text-xs font-bold {{ App::getLocale() == 'gu' ? 'text-blue-600 dark:text-blue-400 bg-blue-50/50 dark:bg-blue-900/20' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700/50' }}">
                                        Gujarati
                                    </button>
                                    <button type="submit" name="language" value="mr" class="w-full text-left px-8 py-2 text-xs font-bold {{ App::getLocale() == 'mr' ? 'text-blue-600 dark:text-blue-400 bg-blue-50/50 dark:bg-blue-900/20' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700/50' }}">
                                        Marathi
                                    </button>
                                    <button type="submit" name="language" value="ta" class="w-full text-left px-8 py-2 text-xs font-bold {{ App::getLocale() == 'ta' ? 'text-blue-600 dark:text-blue-400 bg-blue-50/50 dark:bg-blue-900/20' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700/50' }}">
                                        Tamil
                                    </button>
                                    <button type="submit" name="language" value="es" class="w-full text-left px-8 py-2 text-xs font-bold {{ App::getLocale() == 'es' ? 'text-blue-600 dark:text-blue-400 bg-blue-50/50 dark:bg-blue-900/20' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700/50' }}">
                                        Spanish
                                    </button>
                                    <button type="submit" name="language" value="fr" class="w-full text-left px-8 py-2 text-xs font-bold {{ App::getLocale() == 'fr' ? 'text-blue-600 dark:text-blue-400 bg-blue-50/50 dark:bg-blue-900/20' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700/50' }}">
                                        French
                                    </button>
                                </form>
                            </div>
                        </div>

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <x-dropdown-link :href="route('logout')" onclick="event.preventDefault();
                                                this.closest('form').submit();" class="text-red-500 hover:text-red-600 dark:text-red-400 dark:hover:text-red-300 hover:bg-red-50 dark:hover:bg-red-900/20 font-black transition-all">
                                <div class="flex items-center">
                                    <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                    </svg>
                                    {{ __('Log Out') }}
                                </div>
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger -->
            <div class="-mr-2 flex items-center sm:hidden">
                <button @click="open = ! open"
                    class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex"
                            stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round"
                            stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden border-t border-gray-100 dark:border-gray-800 transition-all">
        <div class="pt-2 pb-3 space-y-1 bg-white dark:bg-gray-900 border-b border-gray-100 dark:border-gray-800">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('usage.index')" :active="request()->routeIs('usage.*')">
                {{ __('Stock Usage') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="Auth::user()->hasFeature('analytics') ? route('dashboard.analytics') : route('subscription.index')" :active="request()->routeIs('dashboard.analytics')">
                <div class="flex items-center">
                    {{ __('Analytics') }}
                    @if(!Auth::user()->hasFeature('analytics'))
                        <svg class="h-3 w-3 ml-1 text-gray-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd" /></svg>
                    @endif
                </div>
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="Auth::user()->hasFeature('payments') ? route('dashboard.payments') : route('subscription.index')" :active="request()->routeIs('dashboard.payments')">
                <div class="flex items-center">
                    {{ __('Payment Received') }}
                    @if(!Auth::user()->hasFeature('payments'))
                        <svg class="h-3 w-3 ml-1 text-gray-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd" /></svg>
                    @endif
                </div>
            </x-responsive-nav-link>
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800">
            <div class="px-4">
                <div class="font-bold text-base text-gray-800 dark:text-gray-100">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-gray-500 dark:text-gray-400">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')" :active="request()->routeIs('profile.edit')">
                    {{ __('Account Settings') }}
                </x-responsive-nav-link>
                <!-- Theme Toggle (Mobile) -->
                <div x-data="{ 
                    dark: localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches),
                    toggle() {
                        this.dark = !this.dark;
                        if (this.dark) {
                            document.documentElement.classList.add('dark');
                            localStorage.theme = 'dark';
                        } else {
                            document.documentElement.classList.remove('dark');
                            localStorage.theme = 'light';
                        }
                    }
                }" class="border-b border-gray-100 dark:border-gray-700 px-4 py-3">
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-bold text-gray-700 dark:text-gray-300" x-text="dark ? '{{ __('Dark Mode') }}' : '{{ __('Light Mode') }}'"></span>
                        <button @click="if (!{{ Auth::user()->hasFeature('dark_mode') ? 'true' : 'false' }}) { window.location.href = '{{ route('subscription.index') }}'; return; } toggle()" 
                                :class="dark ? 'theme-toggle theme-toggle--dark' : 'theme-toggle theme-toggle--light'"
                                title="Toggle theme"
                                class="relative">
                            
                            @if(!Auth::user()->hasFeature('dark_mode'))
                                <div class="absolute -top-1 -right-1 z-10 bg-yellow-400 rounded-full p-0.5 shadow-sm border border-white">
                                    <svg class="h-2 w-2 text-white" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd" /></svg>
                                </div>
                            @endif

                            <svg class="theme-toggle__stars" width="20" height="16" viewBox="0 0 20 16">
                                <circle class="theme-toggle__star" cx="4" cy="4" r="1.5"/>
                                <circle class="theme-toggle__star" cx="12" cy="2" r="1"/>
                                <circle class="theme-toggle__star" cx="8" cy="10" r="1.2"/>
                            </svg>
                            <svg class="theme-toggle__clouds" width="20" height="12" viewBox="0 0 20 12">
                                <circle cx="5" cy="8" r="4" fill="white" opacity="0.6"/>
                                <circle cx="10" cy="6" r="5" fill="white" opacity="0.5"/>
                                <circle cx="15" cy="8" r="3.5" fill="white" opacity="0.4"/>
                            </svg>
                            <div class="theme-toggle__thumb">
                                <svg class="theme-toggle__sun" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2.5" stroke-linecap="round">
                                    <circle cx="12" cy="12" r="4"/>
                                    <path d="M12 2v2M12 20v2M4.93 4.93l1.41 1.41M17.66 17.66l1.41 1.41M2 12h2M20 12h2M4.93 19.07l1.41-1.41M17.66 6.34l1.41-1.41"/>
                                </svg>
                                <svg class="theme-toggle__moon" viewBox="0 0 24 24" fill="none" stroke="#334155" stroke-width="2.5" stroke-linecap="round">
                                    <path d="M21 12.79A9 9 0 1111.21 3 7 7 0 0021 12.79z"/>
                                </svg>
                            </div>
                        </button>
                    </div>
                </div>

                <!-- Language Switcher (Mobile) -->
                <div x-data="{ open: false }" class="border-b border-gray-100 dark:border-gray-700">
                    <button @click="open = !open" class="w-full flex items-center justify-between px-4 py-3 text-sm font-bold text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                        <div class="flex items-center">
                            <svg class="h-4 w-4 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5c-.356 2.446-1.276 4.706-2.703 6.643M6.412 9a11.97 11.97 0 002.828 4.605m0 0a12.001 12.001 0 01-3.262 3.064m3.262-3.064l-.505.5" />
                            </svg>
                            {{ __('Language') }}
                        </div>
                        <svg class="h-4 w-4 transform transition-transform duration-200" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    <div x-show="open" x-collapse class="bg-gray-50 dark:bg-gray-800/50 py-1">
                        <form method="POST" action="{{ route('profile.update') }}">
                            @csrf
                            @method('PATCH')
                            <button type="submit" name="language" value="en" class="w-full text-left px-12 py-3 text-xs font-bold {{ App::getLocale() == 'en' ? 'text-blue-600 dark:text-blue-400 bg-blue-50/50 dark:bg-blue-900/20' : 'text-gray-600 dark:text-gray-400' }}">
                                English
                            </button>
                            <button type="submit" name="language" value="hi" class="w-full text-left px-12 py-3 text-xs font-bold {{ App::getLocale() == 'hi' ? 'text-blue-600 dark:text-blue-400 bg-blue-50/50 dark:bg-blue-900/20' : 'text-gray-600 dark:text-gray-400' }}">
                                Hindi
                            </button>
                            <button type="submit" name="language" value="gu" class="w-full text-left px-12 py-3 text-xs font-bold {{ App::getLocale() == 'gu' ? 'text-blue-600 dark:text-blue-400 bg-blue-50/50 dark:bg-blue-900/20' : 'text-gray-600 dark:text-gray-400' }}">
                                Gujarati
                            </button>
                            <button type="submit" name="language" value="mr" class="w-full text-left px-12 py-3 text-xs font-bold {{ App::getLocale() == 'mr' ? 'text-blue-600 dark:text-blue-400 bg-blue-50/50 dark:bg-blue-900/20' : 'text-gray-600 dark:text-gray-400' }}">
                                Marathi
                            </button>
                            <button type="submit" name="language" value="ta" class="w-full text-left px-12 py-3 text-xs font-bold {{ App::getLocale() == 'ta' ? 'text-blue-600 dark:text-blue-400 bg-blue-50/50 dark:bg-blue-900/20' : 'text-gray-600 dark:text-gray-400' }}">
                                Tamil
                            </button>
                            <button type="submit" name="language" value="es" class="w-full text-left px-12 py-3 text-xs font-bold {{ App::getLocale() == 'es' ? 'text-blue-600 dark:text-blue-400 bg-blue-50/50 dark:bg-blue-900/20' : 'text-gray-600 dark:text-gray-400' }}">
                                Spanish
                            </button>
                            <button type="submit" name="language" value="fr" class="w-full text-left px-12 py-3 text-xs font-bold {{ App::getLocale() == 'fr' ? 'text-blue-600 dark:text-blue-400 bg-blue-50/50 dark:bg-blue-900/20' : 'text-gray-600 dark:text-gray-400' }}">
                                French
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <x-responsive-nav-link :href="route('logout')" onclick="event.preventDefault();
                                        this.closest('form').submit();" class="text-red-600 dark:text-red-400 font-bold">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>

<style>
    .nav-upgrade-btn {
        background: #4f46e5 !important;
        background: linear-gradient(to right, #4f46e5, #3b82f6) !important;
        color: white !important;
        padding: 0.5rem 1.25rem !important;
        border-radius: 12px !important;
        font-weight: 700 !important;
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1) !important;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        border: none !important;
    }

    .nav-upgrade-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        filter: brightness(1.1);
    }

    .premium-badge {
        background: #f1f5f9;
        color: #475569 !important;
        padding: 0.35rem 0.85rem !important;
        border-radius: 9999px !important;
        font-weight: 700 !important;
        border: 1px solid #e2e8f0;
    }
    
    .dark .premium-badge {
        background: #1e293b;
        color: #94a3b8 !important;
        border-color: #334155;
    }
</style>