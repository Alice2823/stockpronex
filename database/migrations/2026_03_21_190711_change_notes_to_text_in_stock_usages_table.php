<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class ChangeNotesToTextInStockUsagesTable extends Migration
{
    public function up()
    {
        DB::statement('ALTER TABLE stock_usages ALTER COLUMN notes TYPE TEXT');
    }

    public function down()
    {
        DB::statement('ALTER TABLE stock_usages ALTER COLUMN notes TYPE VARCHAR(255)');
    }
}