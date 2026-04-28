<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ __('StockProNex') }}</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('images/favicon.svg') }}">

    <!-- Fonts -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap">

    <!-- Tailwind CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
        }
    </script>
    
    <!-- Theme Switcher Script -->
    <script>
        if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark')
        } else {
            document.documentElement.classList.remove('dark')
        }
    </script>
    
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
    <style>
        [x-cloak] { display: none !important; }
        .brand-stock { color: #1f2937; font-weight: bold; }
        .brand-pro { color: #0d6efd; font-weight: bold; }
        .brand-nex { color: #1f2937; font-weight: bold; }

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
    </style>
</head>

<body class="font-sans antialiased text-gray-900 dark:text-gray-100 bg-gray-100 dark:bg-gray-950">
    <div class="min-h-screen bg-gray-100 dark:bg-gray-950">
        @include('layouts.navigation')

        <!-- Page Heading -->
        <header class="bg-white dark:bg-gray-900 shadow border-b border-gray-200 dark:border-gray-800">
            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                {{ $header }}
            </div>
        </header>

        <!-- Page Content -->
        <main>
            {{ $slot }}
        </main>
    </div>

    {{-- AI Assistant Chat Widget --}}
    @auth
        @include('components.ai-assistant')
    @endauth
</body>

</html>