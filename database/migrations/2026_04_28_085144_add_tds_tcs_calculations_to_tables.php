<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTdsTcsCalculationsToTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->decimal('tds_percentage', 5, 2)->default(0)->after('tds_enabled');
            $table->decimal('tcs_percentage', 5, 2)->default(0)->after('tcs_enabled');
        });

        Schema::table('invoices', function (Blueprint $table) {
            $table->decimal('tds_percentage', 5, 2)->default(0)->after('tax_amount');
            $table->decimal('tds_amount', 15, 2)->default(0)->after('tds_percentage');
            $table->decimal('tcs_percentage', 5, 2)->default(0)->after('tds_amount');
            $table->decimal('tcs_amount', 15, 2)->default(0)->after('tcs_percentage');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['tds_percentage', 'tcs_percentage']);
        });

        Schema::table('invoices', function (Blueprint $table) {
            $table->dropColumn(['tds_percentage', 'tds_amount', 'tcs_percentage', 'tcs_amount']);
        });
    }
}
