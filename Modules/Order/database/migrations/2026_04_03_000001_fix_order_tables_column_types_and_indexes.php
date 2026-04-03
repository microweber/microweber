<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Fix order_status_history: order_id and user_id should be unsignedBigInteger
        // to match cart_orders.id (bigIncrements) and users.id (bigIncrements)
        if (Schema::hasTable('order_status_history')) {
            Schema::table('order_status_history', function (Blueprint $table) {
                $table->unsignedBigInteger('order_id')->change();
                $table->unsignedBigInteger('user_id')->nullable()->change();
                $table->index('user_id');
            });
        }

        // Add missing index on order_refunds.refunded_by
        if (Schema::hasTable('order_refunds')) {
            Schema::table('order_refunds', function (Blueprint $table) {
                $table->index('refunded_by');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('order_status_history')) {
            Schema::table('order_status_history', function (Blueprint $table) {
                $table->unsignedInteger('order_id')->change();
                $table->unsignedInteger('user_id')->nullable()->change();
                $table->dropIndex(['user_id']);
            });
        }

        if (Schema::hasTable('order_refunds')) {
            Schema::table('order_refunds', function (Blueprint $table) {
                $table->dropIndex(['refunded_by']);
            });
        }
    }
};
