<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class MigrateOldLegacyRelFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $tableNames =
            [
                'comments',
                'media',
                'content_fields',
                'content_data',
                'custom_fields',
                'categories_items',
            ];





        foreach ($tableNames as $table) {

            if (Schema::hasTable($table)) {

                $checkRel = Schema::hasColumn($table, 'rel');
                $checkHasNoRelType = Schema::hasColumn($table, 'rel_type');
                if ($checkRel) {
                    if (!$checkHasNoRelType) {
                        Schema::table($table, function (Blueprint $table) {
                            $table->renameColumn('rel', 'rel_type');
                        });
                    } else {
                        $fields = DB::table($table)->whereNull('rel_type')->whereNotNull('rel')->get();
                        if ($fields) {
                            foreach ($fields as $item) {
                                DB::table($table)->where('id', $item->id)->update(['rel_type' => $item->rel]);
                            }
                        }
                    }
                }


                $checkUpdatedAt = Schema::hasColumn($table, 'updated_at');
                $checkCreatedAt = Schema::hasColumn($table, 'created_at');
                if (!$checkUpdatedAt) {
                    Schema::table($table, function (Blueprint $table) {
                        $table->timestamp('updated_at')->nullable();
                    });
                }
                if (!$checkCreatedAt) {
                    Schema::table($table, function (Blueprint $table) {
                        $table->timestamp('created_at')->nullable();
                    });
                }


                $checkUpdatedOn = Schema::hasColumn($table, 'updated_on');
                $checkCreatedOn = Schema::hasColumn($table, 'created_on');
                //update updated_at from updated_on

                if ($checkUpdatedOn) {
                    $fields = DB::table($table)->whereNotNull('updated_on')->get();
                    if ($fields) {
                        foreach ($fields as $item) {
                            DB::table($table)->where('id', $item->id)->update(['updated_at' => $item->updated_on]);
                        }
                    }
                }
                if ($checkCreatedOn) {
                    $fields = DB::table($table)->whereNotNull('created_on')->get();
                    if ($fields) {
                        foreach ($fields as $item) {
                            DB::table($table)->where('id', $item->id)->update(['created_at' => $item->created_on]);
                        }
                    }
                }

            }
        }
    }
}
