<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // First, drop the foreign key if it exists, change the column, then re-add it.
        // Or simply try to change the column directly if supported.
        // For simplicity and to avoid doctrine/dbal dependency in Laravel 8:
        Schema::table('whatsapp_logs', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
        });

        DB::statement('ALTER TABLE whatsapp_logs MODIFY user_id BIGINT UNSIGNED NULL');

        Schema::table('whatsapp_logs', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('whatsapp_logs', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
        });

        DB::statement('ALTER TABLE whatsapp_logs MODIFY user_id BIGINT UNSIGNED NOT NULL');

        Schema::table('whatsapp_logs', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }
};
