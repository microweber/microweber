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
        if (!Schema::hasTable('stats_browser_agents')) {
            Schema::create('stats_browser_agents', function (Blueprint $table) {
                $table->id();
                $table->text('browser_agent');
                $table->string('browser_agent_hash');
                $table->string('platform');
                $table->string('platform_version');
                $table->string('browser');
                $table->string('browser_version');
                $table->string('device');
                $table->integer('is_desktop');
                $table->integer('is_mobile');
                $table->integer('is_phone');
                $table->integer('is_tablet');
                $table->text('robot_name');
                $table->string('is_robot');
                $table->string('language');
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
        Schema::dropIfExists('stats_browser_agents');
    }
};
