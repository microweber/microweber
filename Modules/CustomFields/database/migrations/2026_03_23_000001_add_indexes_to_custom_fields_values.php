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
        // On a fresh install, the parent CustomFields migration that creates
        // the custom_fields_values table may run later in the boot order —
        // bail out so the install doesn't crash on the raw CREATE INDEX. The
        // next module:migrate pass picks the indexes up.
        if (!Schema::hasTable('custom_fields_values')) {
            return;
        }
        Schema::table('custom_fields_values', function (Blueprint $table) {
            // Primary index for custom_field_id lookups
            if (!Schema::hasIndex('custom_fields_values', 'custom_fields_values_custom_field_id_index')) {
                $table->index('custom_field_id', 'custom_fields_values_custom_field_id_index');
            }
        });

        // Composite index using raw SQL to support TEXT column prefix.
        // Honour the connection's table prefix so prefixed installs
        // (--db-prefix=…) target the actual table name.
        $prefix = \Illuminate\Support\Facades\DB::connection()->getTablePrefix();
        $table = $prefix . 'custom_fields_values';
        if (!Schema::hasIndex('custom_fields_values', 'custom_fields_values_lookup_index')) {
            if (\Illuminate\Support\Facades\DB::getDriverName() === 'mysql') {
                \Illuminate\Support\Facades\DB::statement(
                    "CREATE INDEX custom_fields_values_lookup_index ON {$table} (custom_field_id, value(191))"
                );
            } else {
                \Illuminate\Support\Facades\DB::statement(
                    "CREATE INDEX custom_fields_values_lookup_index ON {$table} (custom_field_id, value)"
                );
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (!Schema::hasTable('custom_fields_values')) {
            return;
        }
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
