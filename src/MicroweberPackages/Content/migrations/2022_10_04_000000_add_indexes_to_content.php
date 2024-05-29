<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


class AddIndexesToContent  extends Migration
{
    use \MicroweberPackages\Database\Traits\Schema\BlueprintHasIndexTrait;
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //from https://gist.github.com/Razoxane/3bc74900b4eb5c983eb0927fa13b95f5
        Schema::table('content', function (Blueprint $table) {
            try {
                $indexColumns = ['parent', 'is_deleted', 'is_active', 'subtype', 'content_type', 'url', 'title', 'position', 'active_site_template'];
                foreach ($indexColumns as $indexColumn) {
                    $table->index([$indexColumn]);
                }
            } catch (\Exception $e) {
                // do nothing
            }
        });
    }



}
