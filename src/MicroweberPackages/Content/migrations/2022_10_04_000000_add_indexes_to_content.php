<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


class AddIndexesToContent extends Migration
{
    use \MicroweberPackages\Database\Traits\Schema\BlueprintHasIndexTrait;

    public $indexes = [
        'parent',
        'is_deleted',
        'is_active',
        'subtype',
        'content_type',
        'url',
        'title',
        'position',
        'active_site_template'
    ];


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
                $indexColumns = $this->indexes;
                foreach ($indexColumns as $indexColumn) {
                    $index_name = $indexColumn . '_index';
                    if (Schema::hasIndex('content', $index_name)) {
                        continue;
                    }


                    $table->index($indexColumn, $index_name);
                }
            } catch (\Exception $e) {
                // do nothing
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('content', function (Blueprint $table) {
            try {
                $indexColumns = $this->indexes;
                foreach ($indexColumns as $indexColumn) {
                    $index_name = $indexColumn . '_index';
                    if (!Schema::hasIndex('content', $index_name)) {
                        continue;
                    }

                    $table->dropIndex($index_name);
                }
            } catch (\Exception $e) {
                // do nothing
            }
        });
    }


}
