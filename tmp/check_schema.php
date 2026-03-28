<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

try {
    $tables = ['stock_usages', 'invoices'];
    foreach ($tables as $table) {
        echo "Schema for $table:\n";
        $columns = DB::select("DESCRIBE $table");
        foreach ($columns as $column) {
            echo " - {$column->Field}: {$column->Type}\n";
        }
        echo "\n";
    }
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage();
}
