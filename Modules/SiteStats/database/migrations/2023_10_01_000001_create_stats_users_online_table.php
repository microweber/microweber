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
                $table->integer('created_by')->nullable();
                $table->integer('view_count')->nullable()->default(1);
                $table->string('referrer')->nullable();
                $table->string('last_page')->nullable();
                $table->date('visit_date')->nullable();
                $table->time('visit_time')->nullable();
                $table->string('session_id')->nullable();
                $table->string('user_ip')->nullable();
                $table->string('user_id')->nullable();
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
