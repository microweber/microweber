<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('stats_urls')) {
            Schema::create('stats_urls', function (Blueprint $table) {
                $table->id();
                $table->string('url');
                $table->integer('content_id');
                $table->integer('category_id');
                $table->string('url_hash');
                $table->dateTime('updated_at');
                $table->dateTime('created_at');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('stats_urls');
    }
};
