<?php

namespace App\Constants;

class Plan
{
    const FREE = 'free';
    const STANDARD = 'standard';
    const PRO = 'pro';

    const STOCK_LIMITS = [
        self::FREE => 3,
        self::STANDARD => 20,
        self::PRO => -1, // Unlimited
    ];

    const PRICES = [
        self::STANDARD => [
            'monthly' => 199,
            'yearly' => 999,
        ],
        self::PRO => [
            'monthly' => 199,
            'yearly' => 999,
        ],
    ];

    public static function getLimit($plan)
    {
        if (config('app.developer_mode')) {
            return -1;
        }
        return self::STOCK_LIMITS[$plan] ?? 3;
    }

    public static function hasFeature($plan, $feature)
    {
        if (config('app.developer_mode')) {
            return true;
        }

        $features = [
            self::FREE => [
                'light_mode' => false,
                'dark_mode' => false,
                'analytics' => false,
                'payments' => false,
                'barcode' => false,
                'profit_manage' => false,
            ],
            self::STANDARD => [
                'light_mode' => true,
                'dark_mode' => true,
                'analytics' => false,
                'payments' => true,
                'barcode' => false,
                'profit_manage' => false,
            ],
            self::PRO => [
                'light_mode' => true,
                'dark_mode' => true,
                'analytics' => true,
                'payments' => true,
                'barcode' => true,
                'profit_manage' => true,
            ],
        ];

        return $features[$plan][$feature] ?? false;
    }
}
