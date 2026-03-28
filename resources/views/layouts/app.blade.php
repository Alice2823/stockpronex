<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>StockProNex</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">

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
            width: 64px;
            height: 32px;
            border-radius: 9999px;
            cursor: pointer;
            border: none;
            outline: none;
            padding: 0;
            transition: background 0.5s cubic-bezier(0.4, 0, 0.2, 1), box-shadow 0.5s ease;
        }
        .theme-toggle--light {
            background: linear-gradient(135deg, #60a5fa, #38bdf8, #7dd3fc);
            box-shadow: 0 0 16px rgba(96, 165, 250, 0.4), inset 0 1px 2px rgba(255,255,255,0.3);
        }
        .theme-toggle--dark {
            background: linear-gradient(135deg, #1e293b, #334155, #1e1b4b);
            box-shadow: 0 0 16px rgba(99, 102, 241, 0.3), inset 0 1px 2px rgba(255,255,255,0.05);
        }
        .theme-toggle__thumb {
            position: absolute;
            top: 3px;
            width: 26px;
            height: 26px;
            border-radius: 50%;
            transition: transform 0.5s cubic-bezier(0.4, 0, 0.2, 1), background 0.5s ease, box-shadow 0.4s ease;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .theme-toggle--light .theme-toggle__thumb {
            transform: translateX(3px);
            background: linear-gradient(135deg, #fbbf24, #f59e0b);
            box-shadow: 0 0 12px rgba(251, 191, 36, 0.6), 0 0 24px rgba(251, 191, 36, 0.3);
        }
        .theme-toggle--dark .theme-toggle__thumb {
            transform: translateX(35px);
            background: linear-gradient(135deg, #e2e8f0, #cbd5e1);
            box-shadow: 0 0 12px rgba(203, 213, 225, 0.4), 0 0 24px rgba(203, 213, 225, 0.2);
        }
        .theme-toggle__sun {
            position: absolute;
            width: 16px;
            height: 16px;
            transition: opacity 0.4s ease, transform 0.5s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .theme-toggle--light .theme-toggle__sun { opacity: 1; transform: rotate(0deg) scale(1); }
        .theme-toggle--dark .theme-toggle__sun { opacity: 0; transform: rotate(180deg) scale(0.5); }
        .theme-toggle__moon {
            position: absolute;
            width: 14px;
            height: 14px;
            transition: opacity 0.4s ease, transform 0.5s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .theme-toggle--light .theme-toggle__moon { opacity: 0; transform: rotate(-180deg) scale(0.5); }
        .theme-toggle--dark .theme-toggle__moon { opacity: 1; transform: rotate(0deg) scale(1); }
        .theme-toggle__stars {
            position: absolute;
            top: 7px;
            left: 10px;
            transition: opacity 0.4s ease 0.1s;
        }
        .theme-toggle--light .theme-toggle__stars { opacity: 0; }
        .theme-toggle--dark .theme-toggle__stars { opacity: 1; }
        .theme-toggle__star {
            fill: white;
            animation: twinkle 2s infinite alternate;
        }
        .theme-toggle__star:nth-child(2) { animation-delay: 0.5s; }
        .theme-toggle__star:nth-child(3) { animation-delay: 1s; }
        @keyframes twinkle {
            0% { opacity: 0.4; }
            100% { opacity: 1; }
        }
        .theme-toggle__clouds {
            position: absolute;
            top: 8px;
            right: 8px;
            transition: opacity 0.4s ease 0.1s;
        }
        .theme-toggle--light .theme-toggle__clouds { opacity: 0.8; }
        .theme-toggle--dark .theme-toggle__clouds { opacity: 0; }
        .theme-toggle:hover { filter: brightness(1.1); }
        .theme-toggle:active .theme-toggle__thumb { width: 30px; }
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
</body>

</html>