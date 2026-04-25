<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // On a fresh install, the parent Cart migration that creates the
        // cart table may run later in the boot order — bail out so the
        // install doesn't crash. The next module:migrate pass picks the
        // indexes up.
        if (!Schema::hasTable('cart')) {
            return;
        }
        Schema::table('cart', function (Blueprint $table) {
            // Index for session-based cart lookups
            if (!Schema::hasIndex('cart', 'cart_session_id_index')) {
                $table->index('session_id', 'cart_session_id_index');
            }

            // Composite index for order completion status queries
            if (!Schema::hasIndex('cart', 'cart_session_order_completed_index')) {
                $table->index(['session_id', 'order_completed'], 'cart_session_order_completed_index');
            }

            // Index for order_id joins
            if (!Schema::hasIndex('cart', 'cart_order_id_index')) {
                $table->index('order_id', 'cart_order_id_index');
            }

            // Index for product lookups
            if (!Schema::hasIndex('cart', 'cart_rel_id_index')) {
                $table->index('rel_id', 'cart_rel_id_index');
            }

            // Composite index for active cart items by session
            if (!Schema::hasIndex('cart', 'cart_active_session_index')) {
                $table->index(['session_id', 'is_active'], 'cart_active_session_index');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (!Schema::hasTable('cart')) {
            return;
        }
        Schema::table('cart', function (Blueprint $table) {
            $indexes = [
                'cart_session_id_index',
                'cart_session_order_completed_index',
                'cart_order_id_index',
                'cart_rel_id_index',
                'cart_active_session_index',
            ];

            foreach ($indexes as $index) {
                if (Schema::hasIndex('cart', $index)) {
                    $table->dropIndex($index);
                }
            }
        });
    }
};
