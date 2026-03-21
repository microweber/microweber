<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Indexes to add to the cart_orders table.
     *
     * @var array
     */
    protected $indexes = [
        'order_status',
        'customer_id',
        'email',
        'is_paid',
        'order_reference_id',
        'created_at',
        'deleted_at',
        'session_id',
    ];

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        if (!Schema::hasTable('cart_orders')) {
            return;
        }

        Schema::table('cart_orders', function (Blueprint $table) {
            foreach ($this->indexes as $column) {
                $indexName = 'cart_orders_' . $column . '_index';

                // Skip if index already exists
                if (Schema::hasIndex('cart_orders', $indexName)) {
                    continue;
                }

                // Skip if column doesn't exist
                if (!Schema::hasColumn('cart_orders', $column)) {
                    continue;
                }

                $table->index($column, $indexName);
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        if (!Schema::hasTable('cart_orders')) {
            return;
        }

        Schema::table('cart_orders', function (Blueprint $table) {
            foreach ($this->indexes as $column) {
                $indexName = 'cart_orders_' . $column . '_index';

                if (!Schema::hasIndex('cart_orders', $indexName)) {
                    continue;
                }

                $table->dropIndex($indexName);
            }
        });
    }
};
