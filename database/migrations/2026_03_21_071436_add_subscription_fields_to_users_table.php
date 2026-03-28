<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSubscriptionFieldsToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('plan')->default('free')->after('is_subscribed');
            $table->string('billing_cycle')->nullable()->after('plan'); // monthly, yearly
            $table->timestamp('subscription_ends_at')->nullable()->after('billing_cycle');
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
            $table->dropColumn(['plan', 'billing_cycle', 'subscription_ends_at']);
        });
    }
}
