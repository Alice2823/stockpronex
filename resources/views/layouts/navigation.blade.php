<nav x-data="{ open: false }" class="bg-white dark:bg-gray-900 border-b border-gray-100 dark:border-gray-800">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-24">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}" class="flex items-center group transition-all duration-300">
                        <span class="text-2xl sm:text-3xl font-black tracking-tighter uppercase italic leading-none">
                            <span class="brand-stock dark:text-white">{{ __('Stock') }}</span><span class="brand-pro text-blue-600 dark:text-blue-500">{{ __('Pro') }}</span><span class="brand-nex dark:text-gray-400">{{ __('Nex') }}</span>
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
                    <x-nav-link :href="Auth::user()->hasFeature('profit_manage') ? route('dashboard.profit') : route('subscription.index')" :active="request()->routeIs('dashboard.profit')">
                        <div class="flex items-center">
                            {{ __('Profit Manage') }}
                            @if(!Auth::user()->hasFeature('profit_manage'))
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
                               class="nav-upgrade-btn inline-flex items-center px-4 py-2 rounded-lg font-bold text-sm bg-indigo-600 text-white hover:bg-indigo-700 transition-colors shadow-sm">
                                <svg class="w-4 h-4 mr-1.5 text-yellow-300" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                                </svg>
                                {{ __('Upgrade') }}
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Settings Area: Theme Toggle + Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ml-6 sm:space-x-4">
                <!-- Animated Theme Toggle (Standalone in navbar) -->

                <!-- Profile Card Section -->
                <x-dropdown align="right" width="64">
                    <x-slot name="trigger">
                        <button class="flex items-center p-1.5 pr-4 border border-gray-200/50 dark:border-gray-700/50 rounded-xl bg-white/40 dark:bg-gray-900/40 backdrop-blur-md hover:bg-white/60 dark:hover:bg-gray-800/60 hover:shadow-xl hover:-translate-y-0.5 transition-all duration-300 focus:outline-none group">
                            <div class="flex items-center space-x-3">
                                <!-- Enhanced Avatar -->
                                <div class="h-10 w-10 shrink-0 rounded-lg bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-white shadow-md group-hover:scale-105 transition-transform duration-300">
                                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                </div>
                                
                                <!-- Structured Name/Email Stack -->
                                <div class="text-left leading-tight hidden md:block">
                                    <div class="text-sm font-black text-gray-900 dark:text-white uppercase tracking-tight truncate max-w-[120px]">
                                        {{ Auth::user()->name }}
                                    </div>
                                    <div class="text-[10px] font-bold text-gray-500 dark:text-gray-400 truncate max-w-[120px]">
                                        {{ Auth::user()->email }}
                                    </div>
                                </div>

                                <!-- Subtle Dropdown Arrow -->
                                <svg class="h-4 w-4 text-gray-400 group-hover:text-blue-500 transition-colors duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <!-- Dropdown Header -->
                        <div class="px-5 py-4 bg-gray-50/80 dark:bg-gray-900/80 border-b border-gray-100 dark:border-gray-800 backdrop-blur-md rounded-t-xl">
                            <div class="flex items-center space-x-3">
                                <div class="h-10 w-10 rounded-lg bg-blue-50 dark:bg-blue-900/30 flex items-center justify-center text-blue-600 dark:text-blue-400 font-black text-lg">
                                    {{ substr(Auth::user()->name, 0, 1) }}
                                </div>
                                <div>
                                    <p class="text-sm font-black text-gray-900 dark:text-white uppercase tracking-tighter">{{ Auth::user()->name }}</p>
                                    <p class="text-[10px] font-bold text-gray-500 dark:text-gray-400 italic truncate max-w-[150px]">{{ Auth::user()->email }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Menu Items -->
                        <div class="p-1.5">
                            <!-- Account Settings -->
                            <x-dropdown-link :href="route('profile.edit')" class="rounded-lg font-bold text-gray-700 dark:text-gray-300 hover:bg-blue-50 dark:hover:bg-blue-900/20 hover:text-blue-600 dark:hover:text-blue-400 transition-all duration-200">
                                <div class="flex items-center">
                                    <div class="p-1.5 rounded-md bg-blue-100 dark:bg-blue-900/40 text-blue-600 dark:text-blue-400 mr-3">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                    </div>
                                    {{ __('Account Settings') }}
                                </div>
                            </x-dropdown-link>

                            <!-- Theme Toggle -->
                            <div x-data="{
                                dark: localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches),
                                hasAccess: {{ Auth::user()->hasFeature('dark_mode') ? 'true' : 'false' }},
                                toggle() {
                                    if (!this.hasAccess) { window.location.href = '{{ route('subscription.index') }}'; return; }
                                    this.dark = !this.dark;
                                    if (this.dark) { document.documentElement.classList.add('dark'); localStorage.theme = 'dark'; }
                                    else { document.documentElement.classList.remove('dark'); localStorage.theme = 'light'; }
                                }
                            }">
                                <button @click.stop="toggle()" class="w-full flex items-center justify-between px-3 py-2.5 rounded-lg text-sm font-bold text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800/60 transition-colors">
                                    <div class="flex items-center">
                                        <div class="p-1.5 rounded-md bg-amber-100 dark:bg-amber-900/40 text-amber-600 dark:text-amber-400 mr-3">
                                             <div x-show="!dark">
                                                 <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.364 6.364l-.707-.707m12.728 0l-.707.707M6.364 18.364l-.707.707M15 12a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                                             </div>
                                             <div x-show="dark">
                                                 <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z" /></svg>
                                             </div>
                                        </div>
                                        <span x-text="dark ? '{{ __('Dark Mode') }}' : '{{ __('Light Mode') }}'"></span>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        @if(!Auth::user()->hasFeature('dark_mode'))
                                            <svg class="h-3 w-3 text-yellow-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"/></svg>
                                        @endif
                                        <div :class="dark ? 'bg-blue-600' : 'bg-gray-200 dark:bg-gray-700'" class="relative inline-flex h-5 w-9 shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none">
                                            <span :class="dark ? 'translate-x-4' : 'translate-x-0'" class="pointer-events-none inline-block h-4 w-4 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out"></span>
                                        </div>
                                    </div>
                                </button>
                            </div>

                            <!-- Language Switcher -->
                            <div x-data="{ open: false }">
                                <button @click.stop="open = !open" class="w-full flex items-center justify-between px-3 py-2.5 rounded-lg text-sm font-bold text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800/60 transition-colors">
                                    <div class="flex items-center">
                                        <div class="p-1.5 rounded-md bg-emerald-100 dark:bg-emerald-900/40 text-emerald-600 dark:text-emerald-400 mr-3">
                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5c-.356 2.446-1.276 4.706-2.703 6.643M6.412 9a11.97 11.97 0 002.828 4.605m0 0a12.001 12.001 0 01-3.262 3.064m3.262-3.064l-.505.5" />
                                            </svg>
                                        </div>
                                        {{ __('Language') }}
                                    </div>
                                    <svg class="h-4 w-4 transform transition-transform duration-200" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                    </svg>
                                </button>
                                <div x-show="open" x-collapse class="bg-gray-50 dark:bg-gray-800/40 rounded-lg mt-1 mx-1 py-1">
                                    <form method="POST" action="{{ route('profile.update') }}">
                                        @csrf @method('PATCH')
                                        @foreach(['en' => 'English', 'hi' => 'Hindi', 'gu' => 'Gujarati', 'mr' => 'Marathi', 'ta' => 'Tamil', 'es' => 'Spanish', 'fr' => 'French'] as $code => $name)
                                            <button type="submit" name="language" value="{{ $code }}" class="w-full text-left px-10 py-1.5 text-xs font-black {{ App::getLocale() == $code ? 'text-blue-600 dark:text-blue-400 bg-blue-50 dark:bg-blue-900/20' : 'text-gray-500 dark:text-gray-500 hover:text-gray-700' }}">
                                                {{ __($name) }}
                                            </button>
                                        @endforeach
                                    </form>
                                </div>
                            </div>

                            <div class="my-1.5 border-t border-gray-100 dark:border-gray-800"></div>

                            <!-- Logout -->
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <x-dropdown-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();" class="rounded-lg font-black text-red-500 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20">
                                    <div class="flex items-center">
                                        <div class="p-1.5 rounded-md bg-red-100 dark:bg-red-900/40 text-red-600 dark:text-red-400 mr-3">
                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                            </svg>
                                        </div>
                                        {{ __('Logout') }}
                                    </div>
                                </x-dropdown-link>
                            </form>
                        </div>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger -->
            <div class="-mr-2 flex items-center sm:hidden">
                <button @click="open = ! open"
                    class="relative inline-flex items-center justify-center p-2.5 rounded-xl text-gray-500 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 hover:bg-blue-50 dark:hover:bg-blue-900/20 focus:outline-none transition-all duration-300 group overflow-hidden">
                    <div class="relative w-6 h-6 flex items-center justify-center">
                        <!-- Top Bar -->
                        <span :class="open ? 'rotate-45 translate-y-0' : '-translate-y-2'" 
                              class="absolute block w-5 h-0.5 bg-current transform transition-all duration-300 ease-in-out"></span>
                        <!-- Middle Bar -->
                        <span :class="open ? 'opacity-0 -translate-x-4' : 'opacity-100'" 
                              class="absolute block w-5 h-0.5 bg-current transform transition-all duration-300 ease-in-out"></span>
                        <!-- Bottom Bar -->
                        <span :class="open ? '-rotate-45 translate-y-0' : 'translate-y-2'" 
                              class="absolute block w-5 h-0.5 bg-current transform transition-all duration-300 ease-in-out"></span>
                    </div>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu with Smooth Transition and Rounded Corners -->
    <div x-show="open" 
         x-cloak
         x-transition:enter="transition ease-out duration-400"
         x-transition:enter-start="opacity-0 -translate-y-12 scale-95"
         x-transition:enter-end="opacity-100 translate-y-0 scale-100"
         x-transition:leave="transition ease-in duration-250"
         x-transition:leave-start="opacity-100 translate-y-0 scale-100"
         x-transition:leave-end="opacity-0 -translate-y-8 scale-95"
         class="sm:hidden absolute w-full left-0 z-50 overflow-hidden bg-white dark:bg-[#0b0f19] rounded-b-[45px] shadow-[0_25px_70px_-15px_rgba(0,0,0,0.5)] border-b border-gray-200/50 dark:border-gray-800/50">
        <!-- User Profile Card -->
        <div class="px-6 pt-8 pb-6 bg-slate-900 dark:bg-[#0b0f19] relative overflow-hidden">
            <!-- Decorative Background Elements -->
            <div class="absolute top-0 right-0 -mt-4 -mr-4 w-32 h-32 bg-blue-600/20 rounded-full blur-3xl"></div>
            <div class="absolute bottom-0 left-0 -mb-4 -ml-4 w-24 h-24 bg-indigo-600/20 rounded-full blur-2xl"></div>

            <div class="relative flex items-center space-x-4 mb-6">
                <!-- Circular Avatar with Glow -->
                <div class="relative">
                    <div class="absolute inset-0 bg-blue-500 rounded-full blur-md opacity-40"></div>
                    <div class="h-16 w-16 rounded-full bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-white font-black text-2xl border-2 border-white/20 relative shadow-xl">
                        {{ substr(Auth::user()->name, 0, 1) }}
                    </div>
                    @if(Auth::user()->is_subscribed)
                        <div class="absolute -bottom-1 -right-1 h-6 w-6 bg-yellow-400 rounded-full border-2 border-slate-900 flex items-center justify-center shadow-lg">
                            <span class="text-[10px]">⭐</span>
                        </div>
                    @endif
                </div>

                <div class="flex-1 min-w-0">
                    <h2 class="text-lg font-black text-white tracking-tight leading-tight truncate uppercase">
                        {{ Auth::user()->name }}
                    </h2>
                    <p class="text-sm font-medium text-slate-400 truncate tracking-wide">
                        {{ Auth::user()->email }}
                    </p>
                </div>
            </div>
            
            <!-- Upgrade / Status Banner -->
            @if(Auth::user()->is_subscribed)
                <div class="flex items-center justify-between px-4 py-3 rounded-2xl bg-gradient-to-r from-emerald-500/10 to-teal-500/10 border border-emerald-500/20 backdrop-blur-md">
                    <div class="flex items-center">
                        <div class="h-8 w-8 rounded-lg bg-emerald-500/20 flex items-center justify-center text-emerald-400 mr-3">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <span class="text-sm font-bold text-emerald-500 uppercase tracking-wider">{{ __('Premium Member') }}</span>
                    </div>
                </div>
            @else
                <a href="{{ route('subscription.index') }}" 
                   class="premium-upgrade-btn relative group flex items-center justify-center w-full px-4 py-3.5 rounded-2xl font-black text-sm text-slate-900 shadow-2xl transition-all duration-300 active:scale-95 overflow-hidden">
                    <div class="absolute inset-0 bg-gradient-to-r from-amber-300 via-yellow-400 to-amber-300 bg-[length:200%_100%] animate-shimmer"></div>
                    <div class="relative flex items-center">
                        <svg class="w-5 h-5 mr-2 text-slate-900 animate-pulse" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                        </svg>
                        <span class="uppercase tracking-widest">{{ __('Upgrade to Premium') }}</span>
                    </div>
                </a>
            @endif
        </div>

        <!-- Navigation Grid -->
        <div class="p-6 bg-white dark:bg-[#0b0f19]">
            <div class="grid grid-cols-3 gap-3">
                <a href="{{ route('dashboard') }}" class="flex flex-col items-center p-3 rounded-2xl {{ request()->routeIs('dashboard') ? 'bg-blue-50 dark:bg-blue-900/30 border-blue-200 dark:border-blue-800' : 'bg-gray-50 dark:bg-gray-800 border-gray-100 dark:border-gray-700' }} border hover:scale-[1.03] transition-all active:scale-95">
                    <div class="h-10 w-10 rounded-xl {{ request()->routeIs('dashboard') ? 'bg-blue-500 text-white shadow-lg shadow-blue-500/30' : 'bg-gray-200 dark:bg-gray-700 text-gray-500 dark:text-gray-400' }} flex items-center justify-center mb-1.5"><svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg></div>
                    <span class="text-[10px] font-bold {{ request()->routeIs('dashboard') ? 'text-blue-600 dark:text-blue-400' : 'text-gray-500 dark:text-gray-400' }}">{{ __('Dashboard') }}</span>
                </a>
                <a href="{{ route('usage.index') }}" class="flex flex-col items-center p-3 rounded-2xl {{ request()->routeIs('usage.*') ? 'bg-blue-50 dark:bg-blue-900/30 border-blue-200 dark:border-blue-800' : 'bg-gray-50 dark:bg-gray-800 border-gray-100 dark:border-gray-700' }} border hover:scale-[1.03] transition-all active:scale-95">
                    <div class="h-10 w-10 rounded-xl {{ request()->routeIs('usage.*') ? 'bg-blue-500 text-white shadow-lg shadow-blue-500/30' : 'bg-gray-200 dark:bg-gray-700 text-gray-500 dark:text-gray-400' }} flex items-center justify-center mb-1.5"><svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg></div>
                    <span class="text-[10px] font-bold {{ request()->routeIs('usage.*') ? 'text-blue-600 dark:text-blue-400' : 'text-gray-500 dark:text-gray-400' }}">{{ __('Stock') }}</span>
                </a>
                <a href="{{ Auth::user()->hasFeature('analytics') ? route('dashboard.analytics') : route('subscription.index') }}" class="flex flex-col items-center p-3 rounded-2xl {{ request()->routeIs('dashboard.analytics') ? 'bg-blue-50 dark:bg-blue-900/30 border-blue-200 dark:border-blue-800' : 'bg-gray-50 dark:bg-gray-800 border-gray-100 dark:border-gray-700' }} border hover:scale-[1.03] transition-all active:scale-95 relative">
                    @if(!Auth::user()->hasFeature('analytics'))<div class="absolute top-1.5 right-1.5"><svg class="h-3 w-3 text-yellow-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"/></svg></div>@endif
                    <div class="h-10 w-10 rounded-xl {{ request()->routeIs('dashboard.analytics') ? 'bg-blue-500 text-white shadow-lg shadow-blue-500/30' : 'bg-gray-200 dark:bg-gray-700 text-gray-500 dark:text-gray-400' }} flex items-center justify-center mb-1.5"><svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg></div>
                    <span class="text-[10px] font-bold {{ request()->routeIs('dashboard.analytics') ? 'text-blue-600 dark:text-blue-400' : 'text-gray-500 dark:text-gray-400' }}">{{ __('Analytics') }}</span>
                </a>
                <a href="{{ Auth::user()->hasFeature('payments') ? route('dashboard.payments') : route('subscription.index') }}" class="flex flex-col items-center p-3 rounded-2xl {{ request()->routeIs('dashboard.payments') ? 'bg-blue-50 dark:bg-blue-900/30 border-blue-200 dark:border-blue-800' : 'bg-gray-50 dark:bg-gray-800 border-gray-100 dark:border-gray-700' }} border hover:scale-[1.03] transition-all active:scale-95 relative">
                    @if(!Auth::user()->hasFeature('payments'))<div class="absolute top-1.5 right-1.5"><svg class="h-3 w-3 text-yellow-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"/></svg></div>@endif
                    <div class="h-10 w-10 rounded-xl {{ request()->routeIs('dashboard.payments') ? 'bg-blue-500 text-white shadow-lg shadow-blue-500/30' : 'bg-gray-200 dark:bg-gray-700 text-gray-500 dark:text-gray-400' }} flex items-center justify-center mb-1.5"><svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg></div>
                    <span class="text-[10px] font-bold {{ request()->routeIs('dashboard.payments') ? 'text-blue-600 dark:text-blue-400' : 'text-gray-500 dark:text-gray-400' }}">{{ __('Payments') }}</span>
                </a>
                <a href="{{ Auth::user()->hasFeature('profit_manage') ? route('dashboard.profit') : route('subscription.index') }}" class="flex flex-col items-center p-3 rounded-2xl {{ request()->routeIs('dashboard.profit') ? 'bg-blue-50 dark:bg-blue-900/30 border-blue-200 dark:border-blue-800' : 'bg-gray-50 dark:bg-gray-800 border-gray-100 dark:border-gray-700' }} border hover:scale-[1.03] transition-all active:scale-95 relative">
                    @if(!Auth::user()->hasFeature('profit_manage'))<div class="absolute top-1.5 right-1.5"><svg class="h-3 w-3 text-yellow-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"/></svg></div>@endif
                    <div class="h-10 w-10 rounded-xl {{ request()->routeIs('dashboard.profit') ? 'bg-blue-500 text-white shadow-lg shadow-blue-500/30' : 'bg-gray-200 dark:bg-gray-700 text-gray-500 dark:text-gray-400' }} flex items-center justify-center mb-1.5">
                        @if(Auth::user()->currency == 'INR')
                            <span class="text-xl font-bold font-serif leading-none">₹</span>
                        @elseif(Auth::user()->currency == 'USD')
                            <span class="text-xl font-bold font-serif leading-none">$</span>
                        @else
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        @endif
                    </div>
                    <span class="text-[10px] font-bold {{ request()->routeIs('dashboard.profit') ? 'text-blue-600 dark:text-blue-400' : 'text-gray-500 dark:text-gray-400' }}">{{ __('Profit') }}</span>
                </a>
                <a href="{{ route('profile.edit') }}" class="flex flex-col items-center p-3 rounded-2xl {{ request()->routeIs('profile.edit') ? 'bg-blue-50 dark:bg-blue-900/30 border-blue-200 dark:border-blue-800' : 'bg-gray-50 dark:bg-gray-800 border-gray-100 dark:border-gray-700' }} border hover:scale-[1.03] transition-all active:scale-95">
                    <div class="h-10 w-10 rounded-xl {{ request()->routeIs('profile.edit') ? 'bg-blue-500 text-white shadow-lg shadow-blue-500/30' : 'bg-gray-200 dark:bg-gray-700 text-gray-500 dark:text-gray-400' }} flex items-center justify-center mb-1.5"><svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg></div>
                    <span class="text-[10px] font-bold {{ request()->routeIs('profile.edit') ? 'text-blue-600 dark:text-blue-400' : 'text-gray-500 dark:text-gray-400' }}">{{ __('Settings') }}</span>
                </a>
            </div>
        </div>

        <!-- Quick Settings -->
        <div class="px-6 py-6 bg-gray-50/50 dark:bg-gray-800/20 border-t border-gray-100/50 dark:border-gray-800/30">
            <div class="flex flex-col gap-3 mb-4">
                <!-- Dark Mode -->
                <div x-data="{ 
                    dark: localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches),
                    toggle() {
                        this.dark = !this.dark;
                        if (this.dark) { document.documentElement.classList.add('dark'); localStorage.theme = 'dark'; }
                        else { document.documentElement.classList.remove('dark'); localStorage.theme = 'light'; }
                    }
                }" class="flex items-center justify-between p-3.5 rounded-2xl bg-white dark:bg-gray-900 border border-gray-100 dark:border-gray-700 shadow-sm">
                    <span class="text-sm font-bold text-gray-600 dark:text-gray-400 flex items-center" x-text="dark ? '🌙 {{ __('Dark Mode') }}' : '☀️ {{ __('Light Mode') }}'"></span>
                    <button @click="if (!{{ Auth::user()->hasFeature('dark_mode') ? 'true' : 'false' }}) { window.location.href = '{{ route('subscription.index') }}'; return; } toggle()" 
                            :class="dark ? 'theme-toggle theme-toggle--dark' : 'theme-toggle theme-toggle--light'" class="relative">
                        @if(!Auth::user()->hasFeature('dark_mode'))
                            <div class="absolute -top-1 -right-1 z-10 bg-yellow-400 rounded-full p-0.5 shadow-sm border border-white"><svg class="h-2 w-2 text-white" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"/></svg></div>
                        @endif
                        <div class="theme-toggle__thumb">
                            <svg class="theme-toggle__icon theme-toggle__sun" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="5"></circle><line x1="12" y1="1" x2="12" y2="3"></line><line x1="12" y1="21" x2="12" y2="23"></line><line x1="4.22" y1="4.22" x2="5.64" y2="5.64"></line><line x1="18.36" y1="18.36" x2="19.78" y2="19.78"></line><line x1="1" y1="12" x2="3" y2="12"></line><line x1="21" y1="12" x2="23" y2="12"></line><line x1="4.22" y1="19.78" x2="5.64" y2="18.36"></line><line x1="18.36" y1="5.64" x2="19.78" y2="4.22"></line></svg>
                            <svg class="theme-toggle__icon theme-toggle__moon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"></path></svg>
                        </div>
                    </button>
                </div>
                
                <!-- Language -->
                <div x-data="{ open: false }" class="relative">
                    <button @click="open = !open" class="w-full flex items-center justify-between p-3.5 rounded-2xl bg-white dark:bg-gray-900 border border-gray-100 dark:border-gray-700 shadow-sm focus:outline-none">
                        <span class="text-sm font-bold text-gray-600 dark:text-gray-400 flex items-center">
                            <svg class="h-5 w-5 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5c-.356 2.446-1.276 4.706-2.703 6.643M6.412 9a11.97 11.97 0 002.828 4.605m0 0a12.001 12.001 0 01-3.262 3.064m3.262-3.064l-.505.5" /></svg>
                            {{ __('Language') }}
                        </span>
                        <svg class="h-4 w-4 text-gray-400 transition-transform duration-200" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                    <div x-show="open" x-collapse class="mt-2 bg-white dark:bg-gray-900 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-lg overflow-hidden">
                        <form method="POST" action="{{ route('profile.update') }}" class="p-2">
                            @csrf @method('PATCH')
                            <div class="grid grid-cols-2 gap-1.5">
                                @foreach(['en' => 'English', 'hi' => 'Hindi', 'gu' => 'Gujarati', 'mr' => 'Marathi', 'ta' => 'Tamil', 'es' => 'Spanish', 'fr' => 'French'] as $code => $name)
                                    <button type="submit" name="language" value="{{ $code }}" class="text-left px-3 py-2 rounded-xl text-sm font-bold {{ App::getLocale() == $code ? 'text-blue-600 dark:text-blue-400 bg-blue-50 dark:bg-blue-900/20' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-800' }} transition-colors">{{ __($name) }}</button>
                                @endforeach
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            
            <!-- Logout -->
            <!-- Logout -->
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full flex items-center justify-center p-3.5 rounded-2xl text-sm font-bold text-red-500 bg-white dark:bg-gray-900 border border-red-100 dark:border-red-900/30 hover:bg-red-50 dark:hover:bg-red-900/20 transition-all shadow-sm">
                    <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                    {{ __('Log Out') }}
                </button>
            </form>
        </div>
    </div>
</nav>

<style>
    .nav-upgrade-btn {
        background: #4f46e5 !important;
        border: none !important;
        color: white !important;
    }

    .nav-upgrade-btn:hover {
        background: #4338ca !important;
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

    @keyframes shimmer {
        0% { background-position: -200% 0; }
        100% { background-position: 200% 0; }
    }

    .animate-shimmer {
        animation: shimmer 3s infinite linear;
    }

    .premium-upgrade-btn {
        box-shadow: 0 10px 25px -5px rgba(245, 158, 11, 0.4);
    }

    .premium-upgrade-btn:hover {
        box-shadow: 0 15px 30px -5px rgba(245, 158, 11, 0.6);
        transform: translateY(-2px);
    }
</style>