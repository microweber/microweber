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
        if (!Schema::hasTable('stats_pageviews')) {
            Schema::create('stats_pageviews', function (Blueprint $table) {
                $table->id();
                $table->integer('view_count')->default(1);
                $table->integer('page_id');
                $table->integer('main_page_id');
                $table->integer('parent_page_id');
                $table->integer('category_id');
                $table->dateTime('updated_at');
                $table->string('session_id');
                $table->string('user_ip');
                $table->string('user_id');
                $table->string('referrer');
                $table->string('last_page');
                $table->date('visit_date');
                $table->time('visit_time');
                $table->timestamps();
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
        Schema::dropIfExists('stats_pageviews');
    }
};
