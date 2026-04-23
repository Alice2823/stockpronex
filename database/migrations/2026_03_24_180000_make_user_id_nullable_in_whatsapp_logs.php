<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // Drop foreign key first
        Schema::table('whatsapp_logs', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
        });

        // PostgreSQL syntax to make column nullable
        DB::statement('ALTER TABLE whatsapp_logs ALTER COLUMN user_id DROP NOT NULL');

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
        // Drop foreign key first
        Schema::table('whatsapp_logs', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
        });

        // PostgreSQL syntax to make column NOT NULL
        DB::statement('ALTER TABLE whatsapp_logs ALTER COLUMN user_id SET NOT NULL');

        // Re-add foreign key
        Schema::table('whatsapp_logs', function (Blueprint $table) {
            $table->foreign('user_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade');
        });
    }
};