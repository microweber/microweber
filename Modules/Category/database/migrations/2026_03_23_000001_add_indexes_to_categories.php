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
        Schema::table('categories', function (Blueprint $table) {
            // Index for parent_id lookups (tree operations)
            if (!Schema::hasIndex('categories', 'categories_parent_id_index')) {
                $table->index('parent_id', 'categories_parent_id_index');
            }

            // Composite index for tree queries with data_type
            if (!Schema::hasIndex('categories', 'categories_parent_data_type_index')) {
                $table->index(['parent_id', 'data_type'], 'categories_parent_data_type_index');
            }

            // Index for active category filtering
            if (!Schema::hasIndex('categories', 'categories_is_active_index')) {
                $table->index('is_active', 'categories_is_active_index');
            }

            // Composite index for common tree queries
            if (!Schema::hasIndex('categories', 'categories_tree_lookup_index')) {
                $table->index(['data_type', 'parent_id', 'is_active'], 'categories_tree_lookup_index');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            $indexes = [
                'categories_parent_id_index',
                'categories_parent_data_type_index',
                'categories_is_active_index',
                'categories_tree_lookup_index',
            ];

            foreach ($indexes as $index) {
                if (Schema::hasIndex('categories', $index)) {
                    $table->dropIndex($index);
                }
            }
        });
    }
};
