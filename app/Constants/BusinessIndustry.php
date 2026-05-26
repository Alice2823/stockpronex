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
            'Electronics',
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
            'Clothing',
            'Fruits and Vegetables',
            'Furniture',
            'General Store(Kirana)',
            'Grocery',
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
            'Restaurant',
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

        // 1. Electronic & Tech Tracking (IMEI, Serial, Warranty, Brand, Model, Specs)
        $trackingIndustries = [
            'Mobile and accessories',
            'Electronics',
            'Electrical works',
            'Information Technology',
            'Machinery',
            'Medical Devices',
            'Broadband/ cable/ internet',
            'Battery',
            'Opticals',
            'Automobile',
            'Engineering',
        ];

        if (in_array($industry, $trackingIndustries)) {
            $fields[] = 'brand';
            $fields[] = 'model_number';
            $fields[] = 'serial_number';
            $fields[] = 'warranty';
            
            if (in_array($industry, ['Mobile and accessories', 'Electronics', 'Broadband/ cable/ internet'])) {
                $fields[] = 'imei_number'; // Can be MAC address for broadband
            }
            
            if (in_array($industry, ['Automobile', 'Engineering', 'Electronics', 'Hardware'])) {
                $fields[] = 'part_number';
            }

            if (in_array($industry, ['Battery', 'Electrical works', 'Machinery', 'Engineering'])) {
                $fields[] = 'specification'; // For AH, Voltage, Power, etc.
            }

            if (in_array($industry, ['Medical Devices', 'Safety Equipments'])) {
                $fields[] = 'certification';
            }
        }

        // 2. Perishable & Healthcare (Batch, Expiry, Brand, Composition)
        $expiryIndustries = [
            'Medicine(Pharma)',
            'Medical Devices',
            'Fruits and Vegetables',
            'Dairy (Milk)',
            'Meat',
            'General Store(Kirana)',
            'Grocery',
            'Restaurant',
            'Agriculture',
            'Liquor',
            'Paints',
            'Cleaning and Pest Control',
        ];

        if (in_array($industry, $expiryIndustries)) {
            $fields[] = 'brand';
            $fields[] = 'batch_number';
            $fields[] = 'expiry_date';
            
            if (in_array($industry, ['Liquor', 'Paints', 'Grocery', 'Dairy (Milk)'])) {
                $fields[] = 'volume';
            }
            
            if (in_array($industry, ['Agriculture', 'Fruits and Vegetables'])) {
                $fields[] = 'variety';
            }

            if (in_array($industry, ['Medicine(Pharma)'])) {
                $fields[] = 'composition';
            }
        }

        // 3. Fashion, Apparel & Furniture (Size, Color, Material, Brand)
        $variantIndustries = [
            'Textiles',
            'Footwear',
            'Clothing',
            'Sports Equipments',
            'Interiors',
            'Furniture',
            'Gift Shop',
            'Opticals',
        ];

        if (in_array($industry, $variantIndustries)) {
            $fields[] = 'brand';
            $fields[] = 'size';
            $fields[] = 'color';
            $fields[] = 'material';
        }

        // 4. Industrial, Construction & Materials (Dimension, Grade, Material, Thickness)
        $industrialIndustries = [
            'Hardware',
            'Building Material and Construction',
            'Plywood',
            'Packaging',
            'Printing',
            'Scrap',
            'Oil And Gas',
            'Engineering',
        ];

        if (in_array($industry, $industrialIndustries)) {
            $fields[] = 'material';
            $fields[] = 'grade';
            $fields[] = 'dimension';
            
            if (in_array($industry, ['Plywood', 'Hardware', 'Building Material and Construction'])) {
                $fields[] = 'thickness';
            }
            
            if (in_array($industry, ['Hardware', 'Engineering', 'Packaging'])) {
                $fields[] = 'brand';
            }
        }

        // 5. Jewellery (Weight, Purity, Hallmark)
        $jewelleryIndustries = [
            'Jewellery',
            'Gold / Jewellery',
        ];

        if (in_array($industry, $jewelleryIndustries)) {
            $fields[] = 'weight';
            $fields[] = 'purity';
            $fields[] = 'hallmark';
            $fields[] = 'making_charges';
        }

        // 6. Generic Product Fields (Brand) - catch all for others
        if (empty($fields) && !in_array($industry, self::INDUSTRIES['Services'])) {
            $fields[] = 'brand';
        }

        return array_unique($fields);
    }
}
