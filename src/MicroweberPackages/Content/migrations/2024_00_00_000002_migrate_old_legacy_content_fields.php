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


            //check in content fields for fieldn with content_body, content, anf title and move them to content table

            $fields = DB::table('content_fields')
                ->where(function ($query) {
                    $query->where('field', 'content_body')
                        ->orWhere('field', 'content')
                        ->orWhere('field', 'title');
                })->where('rel_type', 'content')
                ->get();
            if($fields){
                foreach ($fields as $field){
                    // update content table by rel_id
                    $content = DB::table('content')->where('id', $field->rel_id)->first();
                    if($content){
                        if($field->field == 'content_body'){
                            DB::table('content')->where('id', $field->rel_id)->update(['content_body' => $field->value]);
                        } else if($field->field == 'content'){
                            DB::table('content')->where('id', $field->rel_id)->update(['content' => $field->value]);
                        } else if($field->field == 'title'){
                            DB::table('content')->where('id', $field->rel_id)->update(['title' => $field->value]);
                        }
                        DB::table('content_fields')->where('id', $field->id)->delete();
                    }
                }
            }

        }


    }

}
