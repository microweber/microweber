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
        Schema::table('custom_fields_values', function (Blueprint $table) {
            // Primary index for custom_field_id lookups
            if (!Schema::hasIndex('custom_fields_values', 'custom_fields_values_custom_field_id_index')) {
                $table->index('custom_field_id', 'custom_fields_values_custom_field_id_index');
            }

            // Composite index for common query patterns
            if (!Schema::hasIndex('custom_fields_values', 'custom_fields_values_lookup_index')) {
                $table->index(['custom_field_id', 'value'], 'custom_fields_values_lookup_index');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('custom_fields_values', function (Blueprint $table) {
            $indexes = [
                'custom_fields_values_custom_field_id_index',
                'custom_fields_values_lookup_index',
            ];

            foreach ($indexes as $index) {
                if (Schema::hasIndex('custom_fields_values', $index)) {
                    $table->dropIndex($index);
                }
            }
        });
    }
};
