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
                $table->string('session_id')->nullable();
                $table->string('session_hostname')->nullable();
                $table->string('user_ip')->nullable();
                $table->integer('user_id')->nullable();
                $table->integer('browser_id')->nullable();
                $table->integer('referrer_id')->nullable();
                $table->integer('referrer_domain_id')->nullable();
                $table->integer('referrer_path_id')->nullable();
                $table->integer('geoip_id')->nullable();
                $table->string('language')->nullable();
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
