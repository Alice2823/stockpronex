<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        $driver = DB::getDriverName();

        if ($driver === 'sqlite') {
            return;
        }

        // Drop foreign key first
        Schema::table('whatsapp_logs', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
        });

        if ($driver === 'mysql') {
            DB::statement('ALTER TABLE whatsapp_logs MODIFY user_id BIGINT UNSIGNED NULL');
        } elseif ($driver === 'pgsql') {
            DB::statement('ALTER TABLE whatsapp_logs ALTER COLUMN user_id DROP NOT NULL');
        }

        // Re-add foreign key
        Schema::table('whatsapp_logs', function (Blueprint $table) {
            $table->foreign('user_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade');
        });
    }

    public function down()
    {
        $driver = DB::getDriverName();

        if ($driver === 'sqlite') {
            return;
        }

        // Drop foreign key first
        Schema::table('whatsapp_logs', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
        });

        if ($driver === 'mysql') {
            DB::statement('ALTER TABLE whatsapp_logs MODIFY user_id BIGINT UNSIGNED NOT NULL');
        } elseif ($driver === 'pgsql') {
            DB::statement('ALTER TABLE whatsapp_logs ALTER COLUMN user_id SET NOT NULL');
        }

        // Re-add foreign key
        Schema::table('whatsapp_logs', function (Blueprint $table) {
            $table->foreign('user_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade');
        });
    }
};
