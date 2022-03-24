<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DeleteQueueModule extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        $dir = modules_path() . 'admin/mics/queue';
        $dir = normalize_path($dir);
        if (is_dir($dir)) {
            if (!is_link($dir)) {
                rmdir_recursive($dir,false);
            } else if (is_link($dir)) {
                @unlink($dir);
            }
        }

        $findModule = \MicroweberPackages\Module\Module::where('module', 'admin/mics/queue')->first();
        if ($findModule != null) {
            $findModule->delete();
        }
    }


}
