<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class MigrateOldLegacyContentIsActive extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        if (Schema::hasTable('content')) {

            $fiedlsToConvert = [
                'is_home',
                'is_shop',
                'is_active',
                'is_deleted',
                'require_login',
            ];
            foreach ($fiedlsToConvert as $field) {
                $fields = DB::table('content')->whereNotNull($field)->get();
                if ($fields) {
                    foreach ($fields as $item) {
                        $itemArr = (array)$item;
                        $setval = 1;
                        if (isset($itemArr[$field]) and $itemArr[$field] === 'n') {
                            $setval = 0;
                        } else if (isset($itemArr[$field]) and $itemArr[$field] === 'y') {
                            $setval = 1;
                        } else if (isset($itemArr[$field]) and $itemArr[$field]) {
                            $setval = $itemArr[$field];
                        }

                        DB::table('content')->where('id', $item->id)->update([$field => $setval]);
                    }
                }
            }

            //check if it has updated_at and created_at

            $checkUpdatedAt = Schema::hasColumn('content', 'updated_at');
            $checkCreatedAt = Schema::hasColumn('content', 'created_at');
            if (!$checkUpdatedAt) {
                Schema::table('content', function (Blueprint $table) {
                    $table->timestamp('updated_at')->nullable();
                });
            }
            if (!$checkCreatedAt) {
                Schema::table('content', function (Blueprint $table) {
                    $table->timestamp('created_at')->nullable();
                });
            }


            $checkUpdatedOn = Schema::hasColumn('content', 'updated_on');
            $checkCreatedOn = Schema::hasColumn('content', 'created_on');
            //update updated_at from updated_on

            if ($checkUpdatedOn) {
                $fields = DB::table('content')->whereNotNull('updated_on')->get();
                if ($fields) {
                    foreach ($fields as $item) {
                        DB::table('content')->where('id', $item->id)->update(['updated_at' => $item->updated_on]);
                    }
                }
            }
            if ($checkCreatedOn) {
                $fields = DB::table('content')->whereNotNull('created_on')->get();
                if ($fields) {
                    foreach ($fields as $item) {
                        DB::table('content')->where('id', $item->id)->update(['created_at' => $item->created_on]);
                    }
                }
            }
        }


    }

}
