<?php

return [

    'google' => [
        'client_id' => trim(env('GOOGLE_CLIENT_ID', '')),
        'client_secret' => trim(env('GOOGLE_CLIENT_SECRET', '')),
        'redirect' => trim(env('GOOGLE_REDIRECT_URI', '')),
    ],

    'whatsapp' => [
        'phone_number_id' => trim(env('WHATSAPP_PHONE_NUMBER_ID', '')),
        'access_token' => trim(env('WHATSAPP_ACCESS_TOKEN', '')),
        'business_name' => env('WHATSAPP_BUSINESS_NAME', 'StockProNex'),
        'template_name' => env('WHATSAPP_TEMPLATE_NAME', 'hello_world'),
    ],

    'gemini' => [
        'api_key' => trim(env('GEMINI_API_KEY', '')),
    ],

    'turnstile' => [
        'site_key' => trim(env('TURNSTILE_SITE_KEY', '')),
        'secret_key' => trim(env('TURNSTILE_SECRET_KEY', '')),
    ],

];