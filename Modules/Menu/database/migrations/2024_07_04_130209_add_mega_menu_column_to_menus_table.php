<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('menus', function ($table) {
            $table->integer('enable_mega_menu')->nullable();
            $table->string('menu_item_template')->nullable();
            $table->longText('mega_menu_settings')->nullable();
        });
    }
};
