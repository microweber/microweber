<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class MigrateOldLegacyContentFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        if (Schema::hasTable('content_fields')) {

            $checkRel = Schema::hasColumn('content_fields', 'rel');
            $checkHasNoRelType = Schema::hasColumn('content_fields', 'rel_type');
            if ($checkRel) {
                if (!$checkHasNoRelType) {
                    Schema::table('content_fields', function (Blueprint $table) {
                        $table->renameColumn('rel', 'rel_type');
                    });
                } else {
                    $fields = DB::table('content_fields')->whereNull('rel_type')->whereNotNull('rel')->get();
                    if ($fields) {
                        foreach ($fields as $item) {
                            DB::table('content_fields')->where('id', $item->id)->update(['rel_type' => $item->rel]);
                        }
                    }
                }
            }

        }


    }

}
