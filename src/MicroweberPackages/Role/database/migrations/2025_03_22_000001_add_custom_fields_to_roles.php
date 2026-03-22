<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $tableNames = config('permission.table_names');

        if (Schema::hasTable($tableNames['roles'])) {
            Schema::table($tableNames['roles'], function (Blueprint $table) {
                if (!Schema::hasColumn($table->getTable(), 'description')) {
                    $table->text('description')->nullable()->after('name');
                }
                if (!Schema::hasColumn($table->getTable(), 'color')) {
                    $table->string('color', 50)->nullable()->after('description');
                }
                if (!Schema::hasColumn($table->getTable(), 'is_system')) {
                    $table->boolean('is_system')->default(false)->after('color');
                }
                if (!Schema::hasColumn($table->getTable(), 'sort_order')) {
                    $table->integer('sort_order')->default(0)->after('is_system');
                }
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
        $tableNames = config('permission.table_names');

        if (Schema::hasTable($tableNames['roles'])) {
            Schema::table($tableNames['roles'], function (Blueprint $table) {
                $table->dropColumn(['description', 'color', 'is_system', 'sort_order']);
            });
        }
    }
};
