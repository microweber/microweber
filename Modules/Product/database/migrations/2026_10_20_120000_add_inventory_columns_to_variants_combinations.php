<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * The inventory columns on product_variants_combinations were originally added
 * by 2026_03_22_000001_create_product_inventory_tables, guarded by
 * Schema::hasTable('product_variants_combinations'). That table is created by
 * 2026_03_22_000001_create_product_variant_attributes_table — the SAME
 * timestamp, which sorts AFTER the inventory migration alphabetically
 * ("inventory" < "variant_attributes"), so the guard is false and the columns
 * were silently skipped. Add them here (late + idempotent) so the schema is
 * correct regardless of migration ordering.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('product_variants_combinations')) {
            return;
        }

        Schema::table('product_variants_combinations', function (Blueprint $table) {
            if (!Schema::hasColumn('product_variants_combinations', 'low_stock_threshold')) {
                $table->integer('low_stock_threshold')->default(10)->after('quantity');
            }
            if (!Schema::hasColumn('product_variants_combinations', 'reorder_point')) {
                $table->integer('reorder_point')->default(5)->after('low_stock_threshold');
            }
            if (!Schema::hasColumn('product_variants_combinations', 'reorder_quantity')) {
                $table->integer('reorder_quantity')->default(50)->after('reorder_point');
            }
            if (!Schema::hasColumn('product_variants_combinations', 'last_stock_check')) {
                $table->timestamp('last_stock_check')->nullable()->after('reorder_quantity');
            }
        });
    }

    public function down(): void
    {
        // Non-destructive: leave the columns in place.
    }
};
