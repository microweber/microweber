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
            // Stackable coupons - allow combining with other coupons
            if (!Schema::hasColumn('cart_coupons', 'is_stackable')) {
                $table->boolean('is_stackable')->default(false)->after('is_active')
                    ->comment('Whether this coupon can be combined with other coupons');
            }

            // Customer group restrictions
            if (!Schema::hasColumn('cart_coupons', 'customer_group_ids')) {
                $table->text('customer_group_ids')->nullable()->after('is_stackable')
                    ->comment('Comma-separated list of customer group IDs allowed to use this coupon');
            }

            // Category restrictions
            if (!Schema::hasColumn('cart_coupons', 'category_ids')) {
                $table->text('category_ids')->nullable()->after('customer_group_ids')
                    ->comment('Comma-separated list of category IDs this coupon applies to');
            }

            // Excluded products
            if (!Schema::hasColumn('cart_coupons', 'excluded_product_ids')) {
                $table->text('excluded_product_ids')->nullable()->after('category_ids')
                    ->comment('Comma-separated list of product IDs excluded from this coupon');
            }

            // First-time customer only
            if (!Schema::hasColumn('cart_coupons', 'first_time_only')) {
                $table->boolean('first_time_only')->default(false)->after('excluded_product_ids')
                    ->comment('Only allow for customers with no previous orders');
            }

            // Auto-apply conditions
            if (!Schema::hasColumn('cart_coupons', 'auto_apply')) {
                $table->boolean('auto_apply')->default(false)->after('first_time_only')
                    ->comment('Automatically apply when conditions are met');
            }

            // Free shipping
            if (!Schema::hasColumn('cart_coupons', 'free_shipping')) {
                $table->boolean('free_shipping')->default(false)->after('auto_apply')
                    ->comment('Grant free shipping instead of monetary discount');
            }

            // Maximum discount cap (for percentage coupons)
            if (!Schema::hasColumn('cart_coupons', 'max_discount_amount')) {
                $table->decimal('max_discount_amount', 10, 2)->nullable()->after('discount_value')
                    ->comment('Maximum discount amount for percentage coupons');
            }

            // Usage statistics (denormalized for performance)
            if (!Schema::hasColumn('cart_coupons', 'times_used')) {
                $table->unsignedInteger('times_used')->default(0)->after('max_discount_amount')
                    ->comment('Total number of times this coupon has been used');
            }

            if (!Schema::hasColumn('cart_coupons', 'total_discount_given')) {
                $table->decimal('total_discount_given', 12, 2)->default(0)->after('times_used')
                    ->comment('Total discount amount given by this coupon');
            }

            // Description field for admin notes
            if (!Schema::hasColumn('cart_coupons', 'description')) {
                $table->text('description')->nullable()->after('coupon_name')
                    ->comment('Admin notes or description of the coupon');
            }
        });

        // Add indexes for performance
        Schema::table('cart_coupons', function (Blueprint $table) {
            if (!Schema::hasIndex('cart_coupons', 'cart_coupons_is_active_is_stackable_index')) {
                $table->index(['is_active', 'is_stackable'], 'cart_coupons_is_active_is_stackable_index');
            }
            if (!Schema::hasIndex('cart_coupons', 'cart_coupons_auto_apply_index')) {
                $table->index('auto_apply', 'cart_coupons_auto_apply_index');
            }
        });
    }

    public function down(): void
    {
        if (!Schema::hasTable('cart_coupons')) {
            return;
        }

        Schema::table('cart_coupons', function (Blueprint $table) {
            $columns = [
                'is_stackable',
                'customer_group_ids',
                'category_ids',
                'excluded_product_ids',
                'first_time_only',
                'auto_apply',
                'free_shipping',
                'max_discount_amount',
                'times_used',
                'total_discount_given',
                'description',
            ];

            foreach ($columns as $column) {
                if (Schema::hasColumn('cart_coupons', $column)) {
                    $table->dropColumn($column);
                }
            }

            // Drop indexes
            if (Schema::hasIndex('cart_coupons', 'cart_coupons_is_active_is_stackable_index')) {
                $table->dropIndex('cart_coupons_is_active_is_stackable_index');
            }
            if (Schema::hasIndex('cart_coupons', 'cart_coupons_auto_apply_index')) {
                $table->dropIndex('cart_coupons_auto_apply_index');
            }
        });
    }
};
