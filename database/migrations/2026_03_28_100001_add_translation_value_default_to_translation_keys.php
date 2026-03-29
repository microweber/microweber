<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('translation_keys')) {
            return;
        }

        if (!Schema::hasColumn('translation_keys', 'translation_value_default')) {
            Schema::table('translation_keys', function (Blueprint $table) {
                $table->text('translation_value_default')->nullable()->after('translation_key');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('translation_keys', 'translation_value_default')) {
            Schema::table('translation_keys', function (Blueprint $table) {
                $table->dropColumn('translation_value_default');
            });
        }
    }
};
