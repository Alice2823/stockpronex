<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ __('StockProNex') }} - {{ __('Manage Your Stock Efficiently') }}</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('images/favicon.svg') }}">
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = { darkMode: 'class' }
    </script>

    <!-- Theme Switcher (runs before paint to prevent flash) -->
    <script>
        if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark')
        } else {
            document.documentElement.classList.remove('dark')
        }
    </script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        body { font-family: 'Inter', sans-serif; }
        .brand-stock { font-weight: bold; }
        .brand-pro { color: #0d6efd; font-weight: bold; }
        .brand-nex { font-weight: bold; }

        /* ===== Animated Logo ===== */
        .logo-expand-container { display: flex; justify-content: center; align-items: baseline; flex-direction: row; }
        .logo-word { display: inline-flex; align-items: baseline; flex-direction: row; }
        .logo-letter { display: inline-block; }
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
            0% { max-width: 0; opacity: 0; }
            40% { opacity: 0; }
            100% { max-width: 400px; opacity: 1; }
        }
        .subtitle-fade {
            opacity: 0;
            animation: fadeIn 0.8s ease forwards 2s;
        }
        @keyframes fadeIn {
            0% { opacity: 0; transform: translateY(8px); }
            100% { opacity: 1; transform: translateY(0); }
        }

        /* ===== Animated Theme Toggle ===== */
        .theme-toggle {
            position: relative;
            width: 50px;
            height: 26px;
            border-radius: 9999px;
            cursor: pointer;
            border: none;
            outline: none;
            padding: 0;
            transition: background-color 0.4s ease;
        }
        .theme-toggle--light {
            background-color: #cbd5e1; /* slate-300 */
        }
        .theme-toggle--dark {
            background-color: #334155; /* slate-700 */
        }
        .theme-toggle__thumb {
            position: absolute;
            top: 2px;
            left: 2px;
            width: 22px;
            height: 22px;
            border-radius: 50%;
            transition: transform 0.4s cubic-bezier(0.4, 0, 0.2, 1), background-color 0.4s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.2);
        }
        .theme-toggle--light .theme-toggle__thumb {
            transform: translateX(0);
            background-color: #ffffff;
        }
        .theme-toggle--dark .theme-toggle__thumb {
            transform: translateX(24px);
            background-color: #0f172a; /* slate-900 */
        }
        .theme-toggle__icon {
            position: absolute;
            width: 14px;
            height: 14px;
            transition: opacity 0.4s ease, transform 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .theme-toggle__sun {
            color: #f59e0b; /* amber-500 */
        }
        .theme-toggle--light .theme-toggle__sun {
            opacity: 1;
            transform: rotate(0deg) scale(1);
        }
        .theme-toggle--dark .theme-toggle__sun {
            opacity: 0;
            transform: rotate(-90deg) scale(0.5);
        }
        .theme-toggle__moon {
            color: #fbbf24; /* amber-400 */
        }
        .theme-toggle--light .theme-toggle__moon {
            opacity: 0;
            transform: rotate(90deg) scale(0.5);
        }
        .theme-toggle--dark .theme-toggle__moon {
            opacity: 1;
            transform: rotate(0deg) scale(1);
        }
        .theme-toggle:focus-visible {
            box-shadow: 0 0 0 2px #fff, 0 0 0 4px #3b82f6;
        }

        /* Bento Grid Styles */
        .bento-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 2.5rem;
            padding: 3rem 0;
        }
        @media (max-width: 1024px) {
            .bento-grid {
                grid-template-columns: 1fr;
            }
        }
        @media (max-width: 640px) {
            .bento-grid {
                grid-template-columns: 1fr;
            }
        }
        .bento-card {
            position: relative;
            background: rgba(255, 255, 255, 0.03);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 2rem;
            overflow: hidden;
            transition: all 0.5s cubic-bezier(0.23, 1, 0.32, 1);
        }
        .dark .bento-card {
            background: rgba(15, 23, 42, 0.4);
            border: 1px solid rgba(255, 255, 255, 0.05);
        }
        .bento-card:hover {
            transform: translateY(-5px) scale(1.01);
            border-color: rgba(59, 130, 246, 0.5);
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
        }
        .bento-card::before {
            content: '';
            position: absolute;
            inset: 0;
            background: radial-gradient(circle at var(--mouse-x, 50%) var(--mouse-y, 50%), rgba(59, 130, 246, 0.15), transparent 80%);
            opacity: 0;
            transition: opacity 0.5s;
        }
        .bento-card:hover::before {
            opacity: 1;
        }
        .glass-icon {
            background: rgba(59, 130, 246, 0.1);
            border: 1px solid rgba(59, 130, 246, 0.2);
            backdrop-filter: blur(5px);
        }
    </style>
    <script>
        document.addEventListener('mousemove', e => {
            const cards = document.querySelectorAll('.bento-card');
            cards.forEach(card => {
                const rect = card.getBoundingClientRect();
                const x = e.clientX - rect.left;
                const y = e.clientY - rect.top;
                card.style.setProperty('--mouse-x', `${x}px`);
                card.style.setProperty('--mouse-y', `${y}px`);
            });
        });
    </script>
</head>

<body class="antialiased bg-gray-50 dark:bg-gray-950 text-gray-900 dark:text-gray-100">

    <!-- Navbar -->
    <nav x-data="{ mobileMenuOpen: false }" class="bg-white/80 dark:bg-gray-900/80 backdrop-blur-md border-b border-gray-200 dark:border-gray-800 fixed w-full z-50 top-0 transition-all duration-500">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <a href="/" class="flex items-center group">
                        <span class="text-xl sm:text-2xl font-bold tracking-tight transition-transform group-hover:scale-105">
                            <span class="brand-stock dark:text-white">Stock</span><span class="brand-pro">Pro</span><span class="brand-nex dark:text-gray-400">Nex</span>
                        </span>
                    </a>
                </div>

                <div class="flex items-center space-x-2 sm:space-x-4">
                    <!-- Animated Theme Toggle -->
                    <div x-data="{
                        dark: localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches),
                        hasAccess: @auth {{ Auth::user()->hasFeature('dark_mode') ? 'true' : 'false' }} @else false @endauth,
                        toggle() {
                            if (!this.hasAccess) {
                                window.location.href = '{{ route('register') }}';
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
                                :aria-label="dark ? '{{ __('Switch to light mode') }}' : '{{ __('Switch to dark mode') }}'"
                                title="{{ __('Toggle theme') }}">
                            
                            <template x-if="!hasAccess">
                                <div class="absolute -top-1 -right-1 z-10 bg-yellow-400 rounded-full p-0.5 shadow-sm border border-white">
                                    <svg class="h-2 w-2 text-white" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd" /></svg>
                                </div>
                            </template>

                            <div class="theme-toggle__thumb">
                                <svg class="theme-toggle__icon theme-toggle__sun" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <circle cx="12" cy="12" r="5"></circle>
                                    <line x1="12" y1="1" x2="12" y2="3"></line>
                                    <line x1="12" y1="21" x2="12" y2="23"></line>
                                    <line x1="4.22" y1="4.22" x2="5.64" y2="5.64"></line>
                                    <line x1="18.36" y1="18.36" x2="19.78" y2="19.78"></line>
                                    <line x1="1" y1="12" x2="3" y2="12"></line>
                                    <line x1="21" y1="12" x2="23" y2="12"></line>
                                    <line x1="4.22" y1="19.78" x2="5.64" y2="18.36"></line>
                                    <line x1="18.36" y1="5.64" x2="19.78" y2="4.22"></line>
                                </svg>
                                <svg class="theme-toggle__icon theme-toggle__moon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"></path>
                                </svg>
                            </div>
                        </button>
                    </div>

                    <!-- Desktop Nav -->
                    <div class="hidden md:flex items-center space-x-4">
                        @if (Route::has('login'))
                            @auth
                                <a href="{{ url('/dashboard') }}"
                                    class="text-gray-700 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400 font-medium transition-colors">{{ __('Dashboard') }}</a>
                            @else
                                <a href="{{ route('login') }}" class="text-gray-700 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400 font-medium transition-colors">{{ __('Log in') }}</a>
                                @if (Route::has('register'))
                                    <a href="{{ route('register') }}"
                                        class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium transition shadow-lg shadow-blue-500/20">{{ __('Get Started') }}</a>
                                @endif
                            @endauth
                        @endif
                    </div>

                    <!-- Mobile Menu Button -->
                    <div class="md:hidden flex items-center">
                        <button @click="mobileMenuOpen = !mobileMenuOpen" class="text-gray-600 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 p-2 transition-colors">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path x-show="!mobileMenuOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                                <path x-show="mobileMenuOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Mobile Menu -->
        <div x-show="mobileMenuOpen" 
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 -translate-y-4"
             x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 translate-y-0"
             x-transition:leave-end="opacity-0 -translate-y-4"
             class="md:hidden bg-white dark:bg-gray-900 border-b border-gray-200 dark:border-gray-800 shadow-xl"
             @click.away="mobileMenuOpen = false">
            <div class="px-4 pt-2 pb-6 space-y-2">
                @if (Route::has('login'))
                        @auth
                            <a href="{{ url('/dashboard') }}"
                                class="block px-4 py-3 rounded-xl text-base font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800 hover:text-blue-600 transition-all">{{ __('Dashboard') }}</a>
                        @else
                            <a href="{{ route('login') }}" class="block px-4 py-3 rounded-xl text-base font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800 hover:text-blue-600 transition-all">{{ __('Log in') }}</a>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}"
                                    class="block px-4 py-3 rounded-xl text-base font-medium text-white bg-blue-600 hover:bg-blue-700 shadow-lg shadow-blue-500/20 text-center transition-all">{{ __('Get Started') }}</a>
                            @endif
                        @endauth
                @endif
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <div class="relative bg-white dark:bg-gray-900 pt-28 pb-12 sm:pt-36 sm:pb-16 lg:pt-40 lg:pb-20 overflow-hidden transition-colors duration-500">
        <div class="absolute inset-0 overflow-hidden">
            <video autoplay muted loop playsinline class="w-full h-full object-cover">
                <source src="https://videos.pexels.com/video-files/853963/853963-hd_1920_1080_30fps.mp4"
                    type="video/mp4">
            </video>
            <div class="absolute inset-0 bg-white/90 dark:bg-gray-900/90 transition-colors duration-500"></div>
        </div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="flex flex-col items-center text-center space-y-5">
                <!-- Big StockProNex Branding -->
                <div>
                    <span class="logo-expand-container text-4xl sm:text-6xl md:text-7xl lg:text-8xl font-black tracking-tighter italic leading-none inline-flex">
                        <span class="text-gray-900 dark:text-white logo-word">
                            <span class="logo-letter">{{ __('S') }}</span><span class="logo-rest">{{ __('tock') }}</span>
                        </span>
                        <span class="text-blue-600 logo-word">
                            <span class="logo-letter">{{ __('P') }}</span><span class="logo-rest">{{ __('ro') }}</span>
                        </span>
                        <span class="text-gray-700 dark:text-gray-400 logo-word">
                            <span class="logo-letter">{{ __('N') }}</span><span class="logo-rest">{{ __('ex') }}</span>
                        </span>
                    </span>
                </div>

                <!-- Tagline -->
                <h1 class="subtitle-fade text-2xl sm:text-3xl md:text-4xl lg:text-5xl tracking-tight font-extrabold text-gray-900 dark:text-white leading-tight">
                    <span class="block">{{ __('Manage your inventory') }}</span>
                    <span class="block text-blue-600 mt-1">{{ __('like a Pro') }}</span>
                </h1>

                <!-- Description -->
                <p class="subtitle-fade max-w-2xl text-base sm:text-lg md:text-xl text-gray-500 dark:text-gray-400 leading-relaxed" style="animation-delay: 2.2s;">
                    <span class="font-bold text-gray-900 dark:text-white">{{ __('Stock') }}</span><span class="font-bold text-blue-600">{{ __('Pro') }}</span><span class="font-bold text-gray-700 dark:text-gray-400">{{ __('Nex') }}</span> {{ __('provides the simplest way to track your stock, value, and assets. Secure, fast, and easy to use.') }}
                </p>

                <!-- CTA Buttons -->
                <div class="subtitle-fade flex flex-col sm:flex-row items-center justify-center gap-4 pt-2" style="animation-delay: 2.4s;">
                    <a href="{{ route('register') }}"
                        class="w-full sm:w-auto flex items-center justify-center px-8 py-3 border border-transparent text-base font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 md:py-4 md:text-lg md:px-10 shadow-lg transition-all">
                        {{ __('Start Free Trial') }}
                    </a>
                    <a href="{{ route('login') }}"
                        class="w-full sm:w-auto flex items-center justify-center px-8 py-3 border border-transparent text-base font-medium rounded-md text-blue-600 dark:text-blue-400 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 md:py-4 md:text-lg md:px-10 shadow-lg transition-all">
                        {{ __('Log In') }}
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- New Premium Features Section -->
    <section class="py-24 bg-white dark:bg-gray-950 transition-colors duration-500 overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-20">
                <h2 class="text-blue-600 dark:text-blue-400 font-bold tracking-widest uppercase text-sm mb-4">{{ __('Precision & Intelligence') }}</h2>
                <h3 class="text-4xl sm:text-5xl font-black text-gray-900 dark:text-white tracking-tight">
                    {{ __('Everything you need') }} <br> <span class="text-blue-600">{{ __('to scale your business') }}</span>
                </h3>
            </div>

            <div class="bento-grid">
                <!-- AI Assistant (Full Width Hero) -->
                <div class="bento-card lg:col-span-3 group min-h-[500px] overflow-hidden">
                    <div class="grid grid-cols-1 lg:grid-cols-2 h-full">
                        <div class="p-6 md:p-10 lg:p-20 flex flex-col justify-center relative z-10">
                            <div class="flex items-center space-x-4 mb-8">
                                <div class="h-14 w-14 flex items-center justify-center rounded-2xl glass-icon text-blue-600 shadow-2xl">
                                    <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-[10px] font-black uppercase tracking-[0.3em] text-blue-600/60 mb-1">{{ __('StockPro Engine') }}</p>
                                    <p class="text-sm font-black text-blue-600 uppercase tracking-widest leading-none">{{ __('Cognitive Series 1.5') }}</p>
                                </div>
                            </div>
                            <h4 class="text-5xl sm:text-7xl font-black text-gray-900 dark:text-white mb-8 group-hover:text-blue-600 transition-colors leading-[0.9] tracking-tighter">{{ __('Smart AI') }} <br>{{ __('Predictor') }}</h4>
                            <p class="text-gray-500 dark:text-gray-400 text-xl leading-relaxed mb-12 max-w-xl">
                                {{ __("Powered by Gemini 1.5 Pro. It doesn't just manage stock—it predicts your future sales patterns to ensure you never miss a sale.") }}
                            </p>
                            <div class="flex flex-wrap items-center gap-8">
                                <div class="flex items-center space-x-5">
                                    <div class="h-14 w-14 rounded-2xl bg-blue-600 flex items-center justify-center shadow-[0_20px_40px_-10px_rgba(37,99,235,0.4)] ring-4 ring-blue-500/10">
                                        <svg class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z" />
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-[11px] font-black uppercase tracking-[0.2em] text-gray-400 mb-1">{{ __('Restock Efficiency') }}</p>
                                        <p class="text-2xl font-black text-gray-900 dark:text-white flex items-center gap-2 tracking-tight">
                                            +84.2% <span class="text-xs font-bold text-green-500 bg-green-500/10 px-2 py-0.5 rounded-md">{{ __('Growth') }}</span>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="relative flex items-center justify-center p-12 bg-gray-50/20 dark:bg-gray-900/20 overflow-hidden">
                            <div class="absolute inset-0 bg-[radial-gradient(circle_at_50%_50%,rgba(59,130,246,0.2),transparent_70%)] opacity-50"></div>
                            <div class="relative w-full transform group-hover:scale-110 group-hover:rotate-1 transition-all duration-1000">
                                <img src="{{ asset('images/inventory-dashboard.png') }}" alt="Inventory AI Dashboard" class="w-full h-auto rounded-[2.5rem] shadow-[0_64px_120px_-32px_rgba(0,0,0,0.7)] border border-white/10">
                                <div class="absolute -bottom-6 sm:-bottom-8 left-1/2 -translate-x-1/2 w-[98%] sm:w-[90%] p-4 sm:p-6 rounded-3xl bg-white/10 backdrop-blur-[60px] border border-white/20 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-2 sm:gap-0 shadow-2xl overflow-hidden group/overlay">
                                    <div class="absolute inset-0 bg-gradient-to-r from-blue-500/10 to-transparent"></div>
                                    <div class="relative flex items-center space-x-4">
                                        <div class="h-4 w-4 rounded-full bg-blue-500 shadow-[0_0_15px_rgba(59,130,246,0.8)] animate-pulse"></div>
                                        <div>
                                            <p class="text-[10px] font-black text-white/40 uppercase tracking-[0.2em] mb-0.5">{{ __('Neural Core Active') }}</p>
                                            <p class="text-sm text-white font-black">{{ __('AI Predictive Analysis') }}</p>
                                        </div>
                                    </div>
                                    <div class="relative flex space-x-1 items-end h-8">
                                        <div class="w-1.5 h-3 bg-blue-500/50 rounded-full animate-pulse"></div>
                                        <div class="w-1.5 h-6 bg-blue-500 rounded-full animate-bounce"></div>
                                        <div class="w-1.5 h-4 bg-blue-500/70 rounded-full animate-pulse animation-delay-300"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Precision Scanning (Grid Row) -->
                <div class="bento-card group flex flex-col min-h-[420px]">
                    <div class="p-6 md:p-10 flex-1">
                        <div class="flex items-center justify-between mb-8">
                            <h4 class="text-3xl font-black text-gray-900 dark:text-white leading-[0.9] tracking-tighter">{{ __('Precision') }}<br>{{ __('Scanning') }}</h4>
                            <div class="h-14 w-14 flex items-center justify-center rounded-2xl glass-icon text-blue-600">
                                <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z" />
                                </svg>
                            </div>
                        </div>
                        <p class="text-gray-500 dark:text-gray-400 text-base leading-relaxed mb-10">
                            {{ __('Professional barcode tracking using your phone\'s built-in camera.') }}
                        </p>
                        <div class="relative mt-auto overflow-hidden rounded-3xl bg-gray-50 dark:bg-gray-900 border border-gray-100 dark:border-gray-800 shadow-inner group-hover:border-blue-500/30 transition-all duration-500">
                            <img src="{{ asset('images/barcode-feature-v2.png') }}" alt="Scanning" class="w-full h-auto transform group-hover:scale-110 group-hover:rotate-3 transition-all duration-1000">
                        </div>
                    </div>
                </div>

                <!-- Financial Growth (Grid Row) -->
                <div class="bento-card group flex flex-col min-h-[420px]">
                    <div class="p-6 md:p-10 flex-1">
                        <div class="flex items-center justify-between mb-8">
                            <h4 class="text-3xl font-black text-gray-900 dark:text-white leading-[0.9] tracking-tighter">{{ __('Financial') }}<br>{{ __('Growth') }}</h4>
                            <div class="h-14 w-14 flex items-center justify-center rounded-2xl glass-icon text-blue-600">
                                <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z" />
                                </svg>
                            </div>
                        </div>
                        <p class="text-gray-500 dark:text-gray-400 text-base leading-relaxed mb-10">
                            {{ __('Real-time profit analytics and expense monitoring dashboards.') }}
                        </p>
                        <div class="relative mt-auto overflow-hidden rounded-3xl bg-gray-50 dark:bg-gray-900 border border-gray-100 dark:border-gray-800 shadow-inner group-hover:border-blue-500/30 transition-all duration-500">
                            <img src="{{ asset('images/profit-feature.png') }}" alt="Growth" class="w-full h-auto transform group-hover:scale-110 group-hover:-rotate-3 transition-all duration-1000">
                        </div>
                    </div>
                </div>

                <!-- WhatsApp (Grid Row) -->
                <div class="bento-card group flex flex-col min-h-[420px]">
                    <div class="p-6 md:p-10 flex-1">
                        <div class="flex items-center justify-between mb-8">
                            <h4 class="text-3xl font-black text-gray-900 dark:text-white leading-[0.9] tracking-tighter">{{ __('Automated') }}<br>{{ __('Delivery') }}</h4>
                            <div class="h-14 w-14 flex items-center justify-center rounded-2xl glass-icon text-green-500 group-hover:bg-green-500/20 transition-all duration-300">
                                <svg class="h-8 w-8" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.246 2.248 3.484 5.232 3.481 8.415-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/>
                                </svg>
                            </div>
                        </div>
                        <p class="text-gray-500 dark:text-gray-400 text-base leading-relaxed mb-10">
                            {{ __('Instant WhatsApp notifications for every invoice generated.') }}
                        </p>
                        <div class="relative mt-auto overflow-hidden rounded-3xl bg-gray-50 dark:bg-gray-900 border border-gray-100 dark:border-gray-800 shadow-inner group-hover:border-green-500/30 transition-all duration-500">
                            <img src="{{ asset('images/whatsapp-feature.png') }}" alt="WhatsApp" class="w-full h-auto transform group-hover:scale-110 transition-all duration-1000">
                        </div>
                    </div>
                </div>

                <!-- Enterprise Secure (Small Row) -->
                <div class="bento-card group p-6 md:p-10 min-h-[250px] flex items-center">
                    <div class="flex flex-col h-full w-full">
                        <div class="flex items-center justify-between mb-auto">
                            <div class="h-16 w-16 flex items-center justify-center rounded-3xl glass-icon text-blue-500 shadow-xl group-hover:bg-blue-500/20 transition-all">
                                <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                </svg>
                            </div>
                            <span class="px-4 py-1.5 rounded-full bg-blue-500/10 text-blue-500 text-[10px] font-black uppercase tracking-widest border border-blue-500/20">{{ __('Secured') }}</span>
                        </div>
                        <h4 class="text-2xl font-black text-gray-900 dark:text-white mt-8 tracking-tighter">{{ __('Enterprise') }}<br>{{ __('Security') }}</h4>
                    </div>
                </div>

                <!-- Social Sync (Small Row - Wide) -->
                <div class="bento-card group p-6 md:p-10 min-h-[250px] lg:col-span-2 flex items-center">
                    <div class="flex items-center justify-between w-full h-full">
                        <div class="w-full lg:max-w-[70%]">
                            <div class="flex gap-4 mb-6">
                                <span class="px-4 py-2 bg-white dark:bg-gray-950 border border-gray-200 dark:border-gray-800 rounded-xl text-xs font-black flex items-center gap-3 shadow-xl group-hover:border-blue-500/50 transition-all">
                                   <svg viewBox="0 0 24 24" class="h-4 w-4" xmlns="http://www.w3.org/2000/svg"><path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/><path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/><path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l3.66-2.84z" fill="#FBBC05"/><path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/></svg>
                                   {{ __('Google Global Sync') }}
                                </span>
                            </div>
                            <h4 class="text-3xl font-black text-gray-900 dark:text-white leading-tight tracking-tighter mb-4">{{ __('Instant Cloud') }} <br>{{ __('Access') }}</h4>
                            <p class="text-gray-500 dark:text-gray-400 text-sm">{{ __('One-click login for total synchronization.') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-800 dark:bg-gray-950 transition-colors duration-500">
        <div class="max-w-7xl mx-auto py-12 px-4 overflow-hidden sm:px-6 lg:px-8">
            <p class="mt-8 text-center text-base text-gray-400">
                &copy; 2026 <span class="brand-stock text-white">{{ __('Stock') }}</span><span class="brand-pro">{{ __('Pro') }}</span><span class="brand-nex text-gray-400">{{ __('Nex') }}</span>. {{ __('All rights reserved.') }}
            </p>
        </div>
    </footer>

</body>

</html>