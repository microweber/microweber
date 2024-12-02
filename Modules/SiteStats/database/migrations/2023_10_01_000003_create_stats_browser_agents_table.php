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
                $table->text('browser_agent')->nullable();
                $table->string('browser_agent_hash')->nullable();
                $table->string('platform')->nullable();
                $table->string('platform_version')->nullable();
                $table->string('browser')->nullable();
                $table->string('browser_version')->nullable();
                $table->string('device')->nullable();
                $table->integer('is_desktop')->nullable();
                $table->integer('is_mobile')->nullable();
                $table->integer('is_phone')->nullable();
                $table->integer('is_tablet')->nullable();
                $table->text('robot_name')->nullable();
                $table->string('is_robot')->nullable();
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
        Schema::dropIfExists('stats_browser_agents');
    }
};
