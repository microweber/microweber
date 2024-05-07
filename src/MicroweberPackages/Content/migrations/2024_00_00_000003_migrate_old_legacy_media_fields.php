<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class MigrateOldLegacyMediaFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        if (Schema::hasTable('media')) {

            $checkRel = Schema::hasColumn('media', 'rel');
            $checkHasNoRelType = Schema::hasColumn('media', 'rel_type');
            if ($checkRel) {
                if (!$checkHasNoRelType) {
                    Schema::table('media', function (Blueprint $table) {
                        $table->renameColumn('rel', 'rel_type');
                    });
                } else {
                    $fields = DB::table('media')->whereNull('rel_type')->whereNotNull('rel')->get();
                    if ($fields) {
                        foreach ($fields as $item) {
                            DB::table('media')->where('id', $item->id)->update(['rel_type' => $item->rel]);
                        }
                    }
                }
            }

        }


    }

}
