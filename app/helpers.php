<?php

if (!function_exists('formatIndianNumber')) {
    /**
     * Format a number in the Indian numbering system (Lakhs/Crores).
     *
     * @param float|int $number
     * @param int $decimals
     * @return string
     */
    function formatIndianNumber($number, $decimals = 0) {
        $number = number_format($number, $decimals, '.', ''); 
        $parts = explode('.', $number);
        $integer = $parts[0];
        $decimal = isset($parts[1]) ? '.' . $parts[1] : '';

        if (strlen($integer) <= 3) {
            return $integer . $decimal;
        }

        $lastThree = substr($integer, -3);
        $remaining = substr($integer, 0, -3);
        
        $remaining = strrev($remaining);
        $chunks = str_split($remaining, 2);
        $remaining = strrev(implode(',', $chunks));
        
        return $remaining . ',' . $lastThree . $decimal;
    }
}
