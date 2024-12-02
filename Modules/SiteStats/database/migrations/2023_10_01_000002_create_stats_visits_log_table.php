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
        if (!Schema::hasTable('stats_visits_log')) {
            Schema::create('stats_visits_log', function (Blueprint $table) {
                $table->id();
                $table->integer('url_id');
                $table->integer('referrer_id');
                $table->integer('view_count')->default(1);
                $table->integer('session_id_key');
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
        Schema::dropIfExists('stats_visits_log');
    }
};
