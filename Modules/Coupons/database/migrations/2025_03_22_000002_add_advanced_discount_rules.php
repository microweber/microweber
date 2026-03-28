<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('cart_coupons')) {
            return;
        }

        Schema::table('cart_coupons', function (Blueprint $table) {
            // Buy X Get Y (BOGO) fields
            if (!Schema::hasColumn('cart_coupons', 'bogo_enabled')) {
                $table->boolean('bogo_enabled')->default(false)->after('free_shipping')
                    ->comment('Enable Buy X Get Y discount rule');
            }

            if (!Schema::hasColumn('cart_coupons', 'bogo_buy_quantity')) {
                $table->unsignedInteger('bogo_buy_quantity')->nullable()->after('bogo_enabled')
                    ->comment('Quantity of items to buy (X)');
            }

            if (!Schema::hasColumn('cart_coupons', 'bogo_get_quantity')) {
                $table->unsignedInteger('bogo_get_quantity')->nullable()->after('bogo_buy_quantity')
                    ->comment('Quantity of items to get free/discounted (Y)');
            }

            if (!Schema::hasColumn('cart_coupons', 'bogo_discount_percent')) {
                $table->decimal('bogo_discount_percent', 5, 2)->nullable()->after('bogo_get_quantity')
                    ->comment('Discount percentage on free items (100=free)');
            }

            if (!Schema::hasColumn('cart_coupons', 'bogo_apply_to')) {
                $table->string('bogo_apply_to', 20)->nullable()->after('bogo_discount_percent')
                    ->comment('cheapest, most_expensive, or matching products');
            }

            // Tiered/Volume discount fields
            if (!Schema::hasColumn('cart_coupons', 'tiered_enabled')) {
                $table->boolean('tiered_enabled')->default(false)->after('bogo_apply_to')
                    ->comment('Enable tiered/volume discount rules');
            }

            if (!Schema::hasColumn('cart_coupons', 'tiered_rules')) {
                $table->json('tiered_rules')->nullable()->after('tiered_enabled')
                    ->comment('JSON array of tier thresholds and discounts');
            }

            // Conditional discount fields
            if (!Schema::hasColumn('cart_coupons', 'conditional_rules')) {
                $table->json('conditional_rules')->nullable()->after('tiered_rules')
                    ->comment('JSON array of conditional discount rules');
            }

            // Usage limit per order
            if (!Schema::hasColumn('cart_coupons', 'max_discount_per_order')) {
                $table->decimal('max_discount_per_order', 12, 2)->nullable()->after('uses_per_customer')
                    ->comment('Maximum discount amount per order');
            }

            // Minimum items requirement
            if (!Schema::hasColumn('cart_coupons', 'min_items_count')) {
                $table->unsignedInteger('min_items_count')->nullable()->after('total_amount')
                    ->comment('Minimum number of items in cart');
            }

            // Maximum items limit
            if (!Schema::hasColumn('cart_coupons', 'max_items_count')) {
                $table->unsignedInteger('max_items_count')->nullable()->after('min_items_count')
                    ->comment('Maximum number of items for coupon to apply');
            }

            // Apply discount to shipping
            if (!Schema::hasColumn('cart_coupons', 'discount_shipping')) {
                $table->boolean('discount_shipping')->default(false)->after('free_shipping')
                    ->comment('Apply discount to shipping cost');
            }
        });

        // Add indexes for performance
        Schema::table('cart_coupons', function (Blueprint $table) {
            if (!Schema::hasIndex('cart_coupons', 'cart_coupons_bogo_enabled_index')) {
                $table->index('bogo_enabled', 'cart_coupons_bogo_enabled_index');
            }
            if (!Schema::hasIndex('cart_coupons', 'cart_coupons_tiered_enabled_index')) {
                $table->index('tiered_enabled', 'cart_coupons_tiered_enabled_index');
            }
        });

        // Create coupon_rule_usage table for tracking rule-based discounts
        if (!Schema::hasTable('coupon_rule_usage')) {
            Schema::create('coupon_rule_usage', function (Blueprint $table) {
                $table->id();
                $table->unsignedInteger('coupon_id');
                $table->foreign('coupon_id')->references('id')->on('cart_coupons')->onDelete('cascade');
                $table->string('rule_type', 50)->comment('bogo, tiered, conditional');
                $table->json('rule_context')->comment('Context data for the rule application');
                $table->decimal('discount_amount', 12, 2)->default(0);
                $table->timestamp('created_at');
                $table->index(['coupon_id', 'rule_type']);
            });
        }
    }

    public function down(): void
    {
        if (!Schema::hasTable('cart_coupons')) {
            return;
        }

        Schema::table('cart_coupons', function (Blueprint $table) {
            $columns = [
                'bogo_enabled',
                'bogo_buy_quantity',
                'bogo_get_quantity',
                'bogo_discount_percent',
                'bogo_apply_to',
                'tiered_enabled',
                'tiered_rules',
                'conditional_rules',
                'max_discount_per_order',
                'min_items_count',
                'max_items_count',
                'discount_shipping',
            ];

            foreach ($columns as $column) {
                if (Schema::hasColumn('cart_coupons', $column)) {
                    $table->dropColumn($column);
                }
            }

            // Drop indexes
            if (Schema::hasIndex('cart_coupons', 'cart_coupons_bogo_enabled_index')) {
                $table->dropIndex('cart_coupons_bogo_enabled_index');
            }
            if (Schema::hasIndex('cart_coupons', 'cart_coupons_tiered_enabled_index')) {
                $table->dropIndex('cart_coupons_tiered_enabled_index');
            }
        });

        Schema::dropIfExists('coupon_rule_usage');
    }
};
