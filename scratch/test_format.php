<?php
$number = 3230000;
$fmt = new NumberFormatter('en_IN', NumberFormatter::DECIMAL);
echo $fmt->format($number) . "\n";

$number2 = 200600;
echo $fmt->format($number2) . "\n";
