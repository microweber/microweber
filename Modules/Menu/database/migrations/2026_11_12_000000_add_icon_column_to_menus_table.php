<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('menus') && !Schema::hasColumn('menus', 'icon')) {
            Schema::table('menus', function (Blueprint $table) {
                // Per-item icon. Stores an icon token (e.g. "fa-rocket",
                // "mdi-home", "mw-...") or raw markup; rendered via icon_html().
                $table->text('icon')->nullable();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasTable('menus') && Schema::hasColumn('menus', 'icon')) {
            Schema::table('menus', function (Blueprint $table) {
                $table->dropColumn('icon');
            });
        }
    }
};
