<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class MigrateOldLegacyCustomFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        if (Schema::hasTable('custom_fields')) {

            $checkRel = Schema::hasColumn('custom_fields', 'rel');
            $checkHasNoRelType = Schema::hasColumn('custom_fields', 'rel_type');
            if ($checkRel) {
                if (!$checkHasNoRelType) {
                    Schema::table('custom_fields', function (Blueprint $table) {
                        $table->renameColumn('rel', 'rel_type');
                    });
                } else {
                    $fields = DB::table('custom_fields')->whereNull('rel_type')->whereNotNull('rel')->get();
                    if ($fields) {
                        foreach ($fields as $item) {
                            DB::table('custom_fields')->where('id', $item->id)->update(['rel_type' => $item->rel]);
                        }
                    }
                }
            }


            $checkUpdatedAt = Schema::hasColumn('custom_fields', 'updated_at');
            $checkCreatedAt = Schema::hasColumn('custom_fields', 'created_at');
            if (!$checkUpdatedAt) {
                Schema::table('custom_fields', function (Blueprint $table) {
                    $table->timestamp('updated_at')->nullable();
                });
            }
            if (!$checkCreatedAt) {
                Schema::table('custom_fields', function (Blueprint $table) {
                    $table->timestamp('created_at')->nullable();
                });
            }


            $checkUpdatedOn = Schema::hasColumn('custom_fields', 'updated_on');
            $checkCreatedOn = Schema::hasColumn('custom_fields', 'created_on');
            //update updated_at from updated_on

            if ($checkUpdatedOn) {
                $fields = DB::table('custom_fields')->whereNotNull('updated_on')->get();
                if ($fields) {
                    foreach ($fields as $item) {
                        DB::table('custom_fields')->where('id', $item->id)->update(['updated_at' => $item->updated_on]);
                    }
                }
            }
            if ($checkCreatedOn) {
                $fields = DB::table('custom_fields')->whereNotNull('created_on')->get();
                if ($fields) {
                    foreach ($fields as $item) {
                        DB::table('custom_fields')->where('id', $item->id)->update(['created_at' => $item->created_on]);
                    }
                }
            }

        }


    }

}
