<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class MigrateOldLegacyCategoriesIsActive extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        if (Schema::hasTable('categories')) {

            $fiedlsToConvert = [
                'is_deleted',
            ];
            foreach ($fiedlsToConvert as $field) {
                $fields = DB::table('categories')->whereNotNull($field)->get();
                if ($fields) {
                    foreach ($fields as $item) {
                        $itemArr = (array)$item;

                        if (isset($itemArr[$field]) and $itemArr[$field] === 'n') {
                            DB::table('categories')->where('id', $item->id)->update([$field => 0]);
                        } else if (isset($itemArr[$field]) and $itemArr[$field] === 'y') {
                            DB::table('categories')->where('id', $item->id)->update([$field => 1]);
                        }


                    }
                }
            }


            //add slug if none
            $checkSlug = Schema::hasColumn('categories', 'url');
            if($checkSlug){
                $fields = DB::table('categories')->whereNull('url')->get();
                if ($fields) {
                    foreach ($fields as $item) {
                        $itemArr = (array)$item;
                        $slug = str_slug($itemArr['title']);
                        // check existing and append id
                        $check = DB::table('categories')->where(['url' => $slug])->first();
                        if($check){
                            $slug = $slug .'-'.$itemArr['id'];
                        }
                        DB::table('categories')->where('id', $item->id)->update(['url' => $slug]);
                    }
                }
            }



            //check if it has updated_at and created_at

            $checkUpdatedAt = Schema::hasColumn('categories', 'updated_at');
            $checkCreatedAt = Schema::hasColumn('categories', 'created_at');
            if (!$checkUpdatedAt) {
                Schema::table('categories', function (Blueprint $table) {
                    $table->timestamp('updated_at')->nullable();
                });
            }
            if (!$checkCreatedAt) {
                Schema::table('categories', function (Blueprint $table) {
                    $table->timestamp('created_at')->nullable();
                });
            }


            $checkUpdatedOn = Schema::hasColumn('categories', 'updated_on');
            $checkCreatedOn = Schema::hasColumn('categories', 'created_on');
            //update updated_at from updated_on

            if ($checkUpdatedOn) {
                $fields = DB::table('categories')->whereNotNull('updated_on')->get();
                if ($fields) {
                    foreach ($fields as $item) {
                        DB::table('categories')->where('id', $item->id)->update(['updated_at' => $item->updated_on]);
                    }
                }
            }
            if ($checkCreatedOn) {
                $fields = DB::table('categories')->whereNotNull('created_on')->get();
                if ($fields) {
                    foreach ($fields as $item) {
                        DB::table('categories')->where('id', $item->id)->update(['created_at' => $item->created_on]);
                    }
                }
            }
        }


    }

}
