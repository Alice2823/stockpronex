<?php

namespace App\Constants;

class BusinessIndustry
{
    public const INDUSTRIES = [
        'Services' => [
            'Accounting and Financial Services',
            'Consulting',
            'Doctor / Clinic / Hospital',
            'Education-Schooling/Coaching',
            'Event planning and management',
            'Fitness - Gym and Spa',
            'Home services',
            'Hotels and Hospitality',
            'Photography',
            'Other services',
        ],
        'General Industries' => [
            'Hardware',
            'Mobile and accessories',
            'Agriculture',
            'Automobile',
            'Battery',
            'Broadband/ cable/ internet',
            'Building Material and Construction',
            'Cleaning and Pest Control',
            'Dairy (Milk)',
            'Electrical works',
            'Engineering',
            'Footwear',
            'Fruits and Vegetables',
            'Furniture',
            'General Store(Kirana)',
            'Gift Shop',
            'Information Technology',
            'Interiors',
            'Jewellery',
            'Liquor',
            'Machinery',
            'Meat',
            'Medical Devices',
            'Medicine(Pharma)',
            'Oil And Gas',
            'Opticals',
            'Packaging',
            'Paints',
            'Plywood',
            'Printing',
            'Safety Equipments',
            'Scrap',
            'Sports Equipments',
            'Stationery',
            'Textiles',
            'Others',
        ],
    ];

    public static function getAll(): array
    {
        $all = [];
        foreach (self::INDUSTRIES as $category => $industries) {
            foreach ($industries as $industry) {
                $all[] = $industry;
            }
        }
        return $all;
    }

    public static function getTaxRates(string $industry): array
    {
        // Service-based industries usually have 10% TDS
        if (in_array($industry, self::INDUSTRIES['Services'])) {
            return ['tds' => 10.0, 'tcs' => 0.0];
        }

        // Trade/General industries usually have 0.1% TDS/TCS
        return ['tds' => 0.1, 'tcs' => 0.1];
    }

    public static function getRequiredFields(string $industry): array
    {
        $fields = [];

        // Industries requiring IMEI/Serial
        $trackingIndustries = [
            'Mobile and accessories',
            'Electronics',
            'Electrical works',
            'Information Technology',
            'General Store(Kirana)',
            'Hardware',
            'Automobile',
            'Battery',
        ];

        if (in_array($industry, $trackingIndustries)) {
            $fields[] = 'imei';
            $fields[] = 'serial';
            $fields[] = 'brand';
            $fields[] = 'model';
        }

        // Industries requiring Expiry/Batch
        $expiryIndustries = [
            'Medicine(Pharma)',
            'Medical Devices',
            'Fruits and Vegetables',
            'Dairy (Milk)',
            'Meat',
            'General Store(Kirana)',
            'Restaurant',
            'Agricultural',
            'Agriculture',
            'Grocery',
        ];

        if (in_array($industry, $expiryIndustries)) {
            $fields[] = 'expiry';
            $fields[] = 'batch';
        }

        // Industries requiring Size/Color
        $apparelIndustries = [
            'Textiles',
            'Footwear',
            'Clothing',
        ];

        if (in_array($industry, $apparelIndustries)) {
            $fields[] = 'size';
            $fields[] = 'color';
            $fields[] = 'brand';
        }

        // Industries requiring Weight/Purity
        $jewelleryIndustries = [
            'Jewellery',
            'Gold / Jewellery',
        ];

        if (in_array($industry, $jewelleryIndustries)) {
            $fields[] = 'weight';
            $fields[] = 'purity';
            $fields[] = 'making_charges';
        }

        return $fields;
    }
}
