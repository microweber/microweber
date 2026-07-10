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
        // On a fresh install, the parent ContentData migration that creates
        // the content_data table may run later in the boot order — bail out
        // here so the install doesn't crash on CREATE INDEX for a missing
        // table. The next module:migrate pass picks the indexes up.
        if (!Schema::hasTable('content_data')) {
            return;
        }
        // Add composite index using raw SQL to support TEXT column prefix.
        // Honour the connection's table prefix (the install command exposes
        // it via --db-prefix, so the raw statement must include it or it
        // would target the unprefixed name and fail with "no such table").
        $prefix = \Illuminate\Support\Facades\DB::connection()->getTablePrefix();
        $table = $prefix . 'content_data';
        if (!Schema::hasIndex('content_data', 'content_data_rel_lookup_index')) {
            if (\Illuminate\Support\Facades\DB::getDriverName() === 'mysql') {
                \Illuminate\Support\Facades\DB::statement(
                    "CREATE INDEX content_data_rel_lookup_index ON {$table} (rel_type(191), rel_id(191), field_name(191))"
                );
            } else {
                \Illuminate\Support\Facades\DB::statement(
                    "CREATE INDEX content_data_rel_lookup_index ON {$table} (rel_type, rel_id, field_name)"
                );
            }
        }

        $isMySQL = \Illuminate\Support\Facades\DB::getDriverName() === 'mysql';

        Schema::table('content_data', function (Blueprint $table) use ($isMySQL) {
            $columns = ['rel_type', 'rel_id', 'field_name'];

            foreach ($columns as $column) {
                $indexName = 'content_data_' . $column . '_index';

                if (Schema::hasIndex('content_data', $indexName)) {
                    continue;
                }

                if (!Schema::hasColumn('content_data', $column)) {
                    continue;
                }

                // For TEXT/BLOB columns on MySQL, use a prefix length
                if ($isMySQL) {
                    $columnType = strtolower(Schema::getColumnType('content_data', $column));
                    $textTypes = ['text', 'longtext', 'mediumtext', 'tinytext', 'blob', 'longblob', 'mediumblob'];
                    if (in_array($columnType, $textTypes, true)) {
                        $table->rawIndex("`{$column}`(191)", $indexName);
                        continue;
                    }
                }

                $table->index($column, $indexName);
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (!Schema::hasTable('content_data')) {
            return;
        }
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
