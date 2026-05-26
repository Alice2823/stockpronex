<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddLowStockAlertSentToStocksTable extends Migration
{
    public function up()
    {
        Schema::table('stocks', function (Blueprint $table) {

            $table->boolean('low_stock_alert_sent')
                ->default(false)
                ->after('quantity');

        });
    }

    public function down()
    {
        Schema::table('stocks', function (Blueprint $table) {

            $table->dropColumn('low_stock_alert_sent');

        });
    }
}