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


            //check if it has updated_at and created_at

            $checkUpdatedAt = Schema::hasColumn('media', 'updated_at');
            $checkCreatedAt = Schema::hasColumn('media', 'created_at');
            if (!$checkUpdatedAt) {
                Schema::table('media', function (Blueprint $table) {
                    $table->timestamp('updated_at')->nullable();
                });
            }
            if (!$checkCreatedAt) {
                Schema::table('media', function (Blueprint $table) {
                    $table->timestamp('created_at')->nullable();
                });
            }


            $checkUpdatedOn = Schema::hasColumn('media', 'updated_on');
            $checkCreatedOn = Schema::hasColumn('media', 'created_on');
            //update updated_at from updated_on

            if ($checkUpdatedOn) {
                $fields = DB::table('media')->whereNotNull('updated_on')->get();
                if ($fields) {
                    foreach ($fields as $item) {
                        DB::table('media')->where('id', $item->id)->update(['updated_at' => $item->updated_on]);
                    }
                }
            }
            if ($checkCreatedOn) {
                $fields = DB::table('media')->whereNotNull('created_on')->get();
                if ($fields) {
                    foreach ($fields as $item) {
                        DB::table('media')->where('id', $item->id)->update(['created_at' => $item->created_on]);
                    }
                }
            }

        }


    }

}
