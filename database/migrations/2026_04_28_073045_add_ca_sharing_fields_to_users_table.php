<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCaSharingFieldsToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('ca_sharing_enabled')->default(false)->after('upi_id');
            $table->string('ca_name')->nullable()->after('ca_sharing_enabled');
            $table->string('ca_whatsapp')->nullable()->after('ca_name');
            $table->string('ca_email')->nullable()->after('ca_whatsapp');
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
            $table->dropColumn(['ca_sharing_enabled', 'ca_name', 'ca_whatsapp', 'ca_email']);
        });
    }
}
