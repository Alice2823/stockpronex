<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>StockProNex - Manage Your Stock Efficiently</title>
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

        /* Page transitions */
        *, *::before, *::after {
            transition-property: color, background-color, border-color, box-shadow;
            transition-duration: 0.4s;
            transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
        }
    </style>
</head>

<body class="antialiased bg-gray-50 dark:bg-gray-950 text-gray-900 dark:text-gray-100">

    <!-- Navbar -->
    <nav class="bg-white dark:bg-gray-900 border-b border-gray-200 dark:border-gray-800 fixed w-full z-50 top-0 transition-colors duration-500">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex">
                    <div class="shrink-0 flex items-center">
                        <a href="/" class="flex items-center">
                            <span class="text-2xl font-bold tracking-tight">
                                <span class="brand-stock dark:text-white">Stock</span><span class="brand-pro">Pro</span><span class="brand-nex dark:text-gray-400">Nex</span>
                            </span>
                        </a>
                    </div>
                </div>
                <div class="flex items-center space-x-4">
                    <!-- Animated Theme Toggle -->
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
                    }">
                        <button @click="toggle()" 
                                :class="dark ? 'theme-toggle theme-toggle--dark' : 'theme-toggle theme-toggle--light'"
                                :aria-label="dark ? 'Switch to light mode' : 'Switch to dark mode'"
                                title="Toggle theme">
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

                    @if (Route::has('login'))
                        @auth
                            <a href="{{ url('/dashboard') }}"
                                class="text-gray-700 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400 font-medium transition-colors">Dashboard</a>
                        @else
                            <a href="{{ route('login') }}" class="text-gray-700 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400 font-medium transition-colors">Log in</a>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}"
                                    class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md font-medium transition">Get
                                    Started</a>
                            @endif
                        @endauth
                    @endif
                </div>
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
                    <span class="logo-expand-container text-5xl sm:text-6xl md:text-7xl lg:text-8xl font-black tracking-tighter italic leading-none inline-flex">
                        <span class="text-gray-900 dark:text-white logo-word">
                            <span class="logo-letter">S</span><span class="logo-rest">tock</span>
                        </span>
                        <span class="text-blue-600 logo-word">
                            <span class="logo-letter">P</span><span class="logo-rest">ro</span>
                        </span>
                        <span class="text-gray-700 dark:text-gray-400 logo-word">
                            <span class="logo-letter">N</span><span class="logo-rest">ex</span>
                        </span>
                    </span>
                </div>

                <!-- Tagline -->
                <h1 class="subtitle-fade text-2xl sm:text-3xl md:text-4xl lg:text-5xl tracking-tight font-extrabold text-gray-900 dark:text-white leading-tight">
                    <span class="block">Manage your inventory</span>
                    <span class="block text-blue-600 mt-1">like a Pro</span>
                </h1>

                <!-- Description -->
                <p class="subtitle-fade max-w-2xl text-base sm:text-lg md:text-xl text-gray-500 dark:text-gray-400 leading-relaxed" style="animation-delay: 2.2s;">
                    <span class="font-bold text-gray-900 dark:text-white">Stock</span><span class="font-bold text-blue-600">Pro</span><span class="font-bold text-gray-700 dark:text-gray-400">Nex</span> provides the simplest way to track your stock, value, and assets. Secure, fast, and easy to use.
                </p>

                <!-- CTA Buttons -->
                <div class="subtitle-fade flex flex-col sm:flex-row items-center justify-center gap-4 pt-2" style="animation-delay: 2.4s;">
                    <a href="{{ route('register') }}"
                        class="w-full sm:w-auto flex items-center justify-center px-8 py-3 border border-transparent text-base font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 md:py-4 md:text-lg md:px-10 shadow-lg transition-all">
                        Start Free Trial
                    </a>
                    <a href="{{ route('login') }}"
                        class="w-full sm:w-auto flex items-center justify-center px-8 py-3 border border-transparent text-base font-medium rounded-md text-blue-600 dark:text-blue-400 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 md:py-4 md:text-lg md:px-10 shadow-lg transition-all">
                        Log In
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Feature Grid -->
    <div class="py-12 bg-white dark:bg-gray-900 transition-colors duration-500">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="lg:text-center">
                <h2 class="text-base text-blue-600 dark:text-blue-400 font-semibold tracking-wide uppercase">Features</h2>
                <p class="mt-2 text-3xl leading-8 font-extrabold tracking-tight text-gray-900 dark:text-white sm:text-4xl">
                    Everything you need
                </p>
                <p class="mt-4 max-w-2xl text-xl text-gray-500 dark:text-gray-400 lg:mx-auto">
                    A comprehensive suite of tools designed to streamline your inventory management process.
                </p>
            </div>

            <div class="mt-10">
                <dl class="space-y-10 md:space-y-0 md:grid md:grid-cols-2 md:gap-x-8 md:gap-y-10">
                    <div class="relative">
                        <dt>
                            <div
                                class="absolute flex items-center justify-center h-12 w-12 rounded-md bg-blue-500 text-white">
                                <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9" />
                                </svg>
                            </div>
                            <p class="ml-16 text-lg leading-6 font-medium text-gray-900 dark:text-white">Real-time Tracking</p>
                        </dt>
                        <dd class="mt-2 ml-16 text-base text-gray-500 dark:text-gray-400">
                            Monitor your stock levels in real-time from anywhere in the world.
                        </dd>
                    </div>

                    <div class="relative">
                        <dt>
                            <div
                                class="absolute flex items-center justify-center h-12 w-12 rounded-md bg-blue-500 text-white">
                                <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V17m0 16V9" />
                                </svg>
                            </div>
                            <p class="ml-16 text-lg leading-6 font-medium text-gray-900 dark:text-white">Value Assessment</p>
                        </dt>
                        <dd class="mt-2 ml-16 text-base text-gray-500 dark:text-gray-400">
                            Instantly calculate the total value of your inventory with accurate pricing tools.
                        </dd>
                    </div>

                    <div class="relative">
                        <dt>
                            <div
                                class="absolute flex items-center justify-center h-12 w-12 rounded-md bg-blue-500 text-white">
                                <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13 10V3L4 14h7v7l9-11h-7z" />
                                </svg>
                            </div>
                            <p class="ml-16 text-lg leading-6 font-medium text-gray-900 dark:text-white">Fast & Secure</p>
                        </dt>
                        <dd class="mt-2 ml-16 text-base text-gray-500 dark:text-gray-400">
                            Enterprise-grade security and blazing fast performance you can rely on.
                        </dd>
                    </div>

                    <div class="relative">
                        <dt>
                            <div
                                class="absolute flex items-center justify-center h-12 w-12 rounded-md bg-blue-500 text-white">
                                <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z" />
                                </svg>
                            </div>
                            <p class="ml-16 text-lg leading-6 font-medium text-gray-900 dark:text-white">Social Login</p>
                        </dt>
                        <dd class="mt-2 ml-16 text-base text-gray-500 dark:text-gray-400">
                            Sign in easily with your Google account. No more forgotten passwords.
                        </dd>
                    </div>
                </dl>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-gray-800 dark:bg-gray-950 transition-colors duration-500">
        <div class="max-w-7xl mx-auto py-12 px-4 overflow-hidden sm:px-6 lg:px-8">
            <p class="mt-8 text-center text-base text-gray-400">
                &copy; 2026 <span class="brand-stock text-white">Stock</span><span class="brand-pro">Pro</span><span class="brand-nex text-gray-400">Nex</span>. All rights reserved.
            </p>
        </div>
    </footer>

</body>

</html>