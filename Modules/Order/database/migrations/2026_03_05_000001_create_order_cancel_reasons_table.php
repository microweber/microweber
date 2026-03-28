<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('order_cancel_reasons')) {
            Schema::create('order_cancel_reasons', function (Blueprint $table) {
                $table->id();
                $table->unsignedInteger('user_id')->nullable();
                $table->unsignedInteger('order_id')->nullable();
                $table->string('stripe_session_id')->nullable();
                $table->text('reason')->nullable();
                $table->string('ip_address')->nullable();
                $table->timestamps();
                
                $table->index('user_id');
                $table->index('order_id');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('order_cancel_reasons');
    }
};
