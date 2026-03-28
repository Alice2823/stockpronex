<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

$columns = DB::select("DESCRIBE invoices");
$output = "Schema for invoices:\n";
foreach ($columns as $column) {
    $output .= " - {$column->Field}: {$column->Type}\n";
}
file_put_contents('tmp/schema_output.txt', $output);
echo "Done\n";
