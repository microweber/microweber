<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DeleteOldBackupModule extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $dir = modules_path() . 'admin/backup_v2/';
        $dir = normalize_path($dir);
        if (is_dir($dir)) {
            if (!is_link($dir)) {
                rmdir_recursive($dir);
            }
        }

        $findModule = \MicroweberPackages\Module\Module::where('module', 'admin/backup_v2')->first();
        if ($findModule != null) {
            $findModule->delete();
        }
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // delete
    }
}
