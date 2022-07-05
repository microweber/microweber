<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateMenusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {


        if (!Schema::hasTable('menus')) {
            Schema::create('menus', function (Blueprint $table) {
                $table->increments('id');
                $table->text('title')->nullable();
                $table->text('item_type')->nullable();
                $table->text('description')->nullable();
                $table->longText('url')->nullable();
                $table->text('url_target')->nullable();
                $table->integer('parent_id')->nullable();
                $table->integer('content_id')->nullable();
                $table->integer('categories_id')->nullable();
                $table->integer('position')->nullable();
                $table->integer('is_active')->nullable();
                $table->integer('auto_populate')->nullable();
                $table->text('size')->nullable();
                $table->text('default_image')->nullable();
                $table->text('rollover_image')->nullable();
                $table->timestamps();
            });
        }

        if (Schema::hasTable('menus')) {
            if (!Schema::hasColumn('menus', 'menu_name')) {
                Schema::table('menus', function ($table) {
                    $table->text('menu_name')->nullable();
                });
            }
        }

        if (Schema::hasTable('menus')) {
            if (Schema::hasColumn('menus', 'menu_name')) {
                $fields = DB::table('menus')
                    ->whereNotNUll('title')
                    ->whereNull('menu_name')
                    ->get();
                if ($fields) {
                    foreach ($fields as $field) {
                        DB::table('menus')
                            ->where('id', $field->id)
                            ->limit(1)
                            ->update(['menu_name' => $field->title]);

                    }
                }
            }
        }
    }
}
