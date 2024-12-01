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
        if (!Schema::hasTable('stats_sessions')) {
            Schema::create('stats_sessions', function (Blueprint $table) {
                $table->id();
                $table->string('session_id');
                $table->string('session_hostname');
                $table->string('user_ip');
                $table->integer('user_id');
                $table->integer('browser_id');
                $table->integer('referrer_id');
                $table->integer('referrer_domain_id');
                $table->integer('referrer_path_id');
                $table->integer('geoip_id');
                $table->string('language');
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
        Schema::dropIfExists('stats_sessions');
    }
};
