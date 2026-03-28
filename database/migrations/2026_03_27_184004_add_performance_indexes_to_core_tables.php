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
        if (Schema::hasTable('content_data')) {
            if (!Schema::hasIndex('content_data', 'content_data_rel_lookup_index')) {
                if (\Illuminate\Support\Facades\DB::getDriverName() === 'mysql') {
                    \Illuminate\Support\Facades\DB::statement(
                        'CREATE INDEX content_data_rel_lookup_index ON content_data (rel_type(191), rel_id(191), field_name(191))'
                    );
                } else {
                    \Illuminate\Support\Facades\DB::statement(
                        'CREATE INDEX content_data_rel_lookup_index ON content_data (rel_type, rel_id, field_name)'
                    );
                }
            }
            Schema::table('content_data', function (Blueprint $table) {
                if (!Schema::hasIndex('content_data', 'content_data_rel_type_index')) {
                    $table->index('rel_type', 'content_data_rel_type_index');
                }
                if (!Schema::hasIndex('content_data', 'content_data_rel_id_index')) {
                    $table->index('rel_id', 'content_data_rel_id_index');
                }
            });
        }

        if (Schema::hasTable('custom_fields_values')) {
            Schema::table('custom_fields_values', function (Blueprint $table) {
                if (!Schema::hasIndex('custom_fields_values', 'custom_fields_values_custom_field_id_index')) {
                    $table->index('custom_field_id', 'custom_fields_values_custom_field_id_index');
                }
            });
        }

        if (Schema::hasTable('cart')) {
            Schema::table('cart', function (Blueprint $table) {
                if (!Schema::hasIndex('cart', 'cart_session_id_index')) {
                    $table->index('session_id', 'cart_session_id_index');
                }
                if (!Schema::hasIndex('cart', 'cart_session_order_completed_index')) {
                    $table->index(['session_id', 'order_completed'], 'cart_session_order_completed_index');
                }
                if (!Schema::hasIndex('cart', 'cart_order_id_index')) {
                    $table->index('order_id', 'cart_order_id_index');
                }
            });
        }

        if (Schema::hasTable('categories')) {
            Schema::table('categories', function (Blueprint $table) {
                if (!Schema::hasIndex('categories', 'categories_parent_id_index')) {
                    $table->index('parent_id', 'categories_parent_id_index');
                }
                if (!Schema::hasIndex('categories', 'categories_parent_data_type_index')) {
                    $table->index(['parent_id', 'data_type'], 'categories_parent_data_type_index');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('content_data')) {
            Schema::table('content_data', function (Blueprint $table) {
                $table->dropIndex('content_data_rel_lookup_index');
                $table->dropIndex('content_data_rel_type_index');
                $table->dropIndex('content_data_rel_id_index');
            });
        }
        if (Schema::hasTable('custom_fields_values')) {
            Schema::table('custom_fields_values', function (Blueprint $table) {
                $table->dropIndex('custom_fields_values_custom_field_id_index');
            });
        }
        if (Schema::hasTable('cart')) {
            Schema::table('cart', function (Blueprint $table) {
                $table->dropIndex('cart_session_id_index');
                $table->dropIndex('cart_session_order_completed_index');
                $table->dropIndex('cart_order_id_index');
            });
        }
        if (Schema::hasTable('categories')) {
            Schema::table('categories', function (Blueprint $table) {
                $table->dropIndex('categories_parent_id_index');
                $table->dropIndex('categories_parent_data_type_index');
            });
        }
    }
};
