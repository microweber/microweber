<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Indexes to add to the product_meta_data table.
     *
     * @var array
     */
    protected $indexes = [
        'product_id',
        'sku',
        'barcode',
        'qty',
        'track_quantity',
        'has_special_price',
    ];

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        if (!Schema::hasTable('product_meta_data')) {
            return;
        }

        Schema::table('product_meta_data', function (Blueprint $table) {
            foreach ($this->indexes as $column) {
                $indexName = 'product_meta_data_' . $column . '_index';

                // Skip if index already exists
                if (Schema::hasIndex('product_meta_data', $indexName)) {
                    continue;
                }

                // Skip if column doesn't exist
                if (!Schema::hasColumn('product_meta_data', $column)) {
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
        if (!Schema::hasTable('product_meta_data')) {
            return;
        }

        Schema::table('product_meta_data', function (Blueprint $table) {
            foreach ($this->indexes as $column) {
                $indexName = 'product_meta_data_' . $column . '_index';

                if (!Schema::hasIndex('product_meta_data', $indexName)) {
                    continue;
                }

                $table->dropIndex($indexName);
            }
        });
    }
};
