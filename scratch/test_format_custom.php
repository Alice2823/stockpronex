<?php
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

echo "3230000 -> " . formatIndianNumber(3230000) . "\n";
echo "200600 -> " . formatIndianNumber(200600) . "\n";
echo "123 -> " . formatIndianNumber(123) . "\n";
echo "1234 -> " . formatIndianNumber(1234) . "\n";
echo "12345 -> " . formatIndianNumber(12345) . "\n";
echo "123456 -> " . formatIndianNumber(123456) . "\n";
echo "1234567 -> " . formatIndianNumber(1234567) . "\n";
echo "1234567.89 (2) -> " . formatIndianNumber(1234567.89, 2) . "\n";
