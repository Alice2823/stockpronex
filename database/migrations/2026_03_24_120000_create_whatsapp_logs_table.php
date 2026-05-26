<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('whatsapp_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('invoice_id');
            $table->unsignedBigInteger('user_id');
            $table->string('phone', 20);
            $table->enum('status', ['pending', 'sent', 'failed'])->default('pending');
            $table->string('message_id')->nullable(); // WhatsApp message ID
            $table->text('error_message')->nullable();
            $table->timestamps();

            $table->foreign('invoice_id')->references('id')->on('invoices')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->index(['invoice_id', 'status']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('whatsapp_logs');
    }
};
