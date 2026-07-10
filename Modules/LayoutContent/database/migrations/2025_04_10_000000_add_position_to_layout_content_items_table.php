<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('layout_content_items', 'position')) {
            if (!Schema::hasTable('layout_content_items')) { return; }

            Schema::table('layout_content_items', function (Blueprint $table) {
                $table->integer('position')->default(0)->after('rel_id');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('layout_content_items', 'position')) {
            Schema::table('layout_content_items', function (Blueprint $table) {
                $table->dropColumn('position');
            });
        }
    }
};
