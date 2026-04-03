<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('media')) {
            Schema::table('media', function (Blueprint $table) {
                // Media is frequently queried by (rel_type, rel_id) for polymorphic lookups
                // EXPLAIN showed full table scan without this index
                if (!$this->hasIndex('media', 'media_rel_type_rel_id_index')) {
                    $table->index(['rel_type', 'rel_id'], 'media_rel_type_rel_id_index');
                }
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('media')) {
            Schema::table('media', function (Blueprint $table) {
                $table->dropIndex('media_rel_type_rel_id_index');
            });
        }
    }

    private function hasIndex(string $table, string $indexName): bool
    {
        $indexes = Schema::getIndexes($table);
        foreach ($indexes as $index) {
            if ($index['name'] === $indexName) {
                return true;
            }
        }
        return false;
    }
};
