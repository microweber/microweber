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
        Schema::table('content_data', function (Blueprint $table) {
            // Check and add composite index for frequent lookups
            if (!Schema::hasIndex('content_data', 'content_data_rel_lookup_index')) {
                $table->index(['rel_type', 'rel_id', 'field_name'], 'content_data_rel_lookup_index');
            }

            // Individual indexes for single-column queries
            if (!Schema::hasIndex('content_data', 'content_data_rel_type_index')) {
                $table->index('rel_type', 'content_data_rel_type_index');
            }

            if (!Schema::hasIndex('content_data', 'content_data_rel_id_index')) {
                $table->index('rel_id', 'content_data_rel_id_index');
            }

            if (!Schema::hasIndex('content_data', 'content_data_field_name_index')) {
                $table->index('field_name', 'content_data_field_name_index');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('content_data', function (Blueprint $table) {
            $indexes = [
                'content_data_rel_lookup_index',
                'content_data_rel_type_index',
                'content_data_rel_id_index',
                'content_data_field_name_index',
            ];

            foreach ($indexes as $index) {
                if (Schema::hasIndex('content_data', $index)) {
                    $table->dropIndex($index);
                }
            }
        });
    }
};
