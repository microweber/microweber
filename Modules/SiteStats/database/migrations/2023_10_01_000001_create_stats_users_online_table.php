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
        if (!Schema::hasTable('stats_users_online')) {
            Schema::create('stats_users_online', function (Blueprint $table) {
                $table->id();
                $table->integer('created_by');
                $table->integer('view_count')->default(1);
                $table->string('referrer');
                $table->string('last_page');
                $table->date('visit_date');
                $table->time('visit_time');
                $table->string('session_id');
                $table->string('user_ip');
                $table->string('user_id');
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
        Schema::dropIfExists('stats_users_online');
    }
};
