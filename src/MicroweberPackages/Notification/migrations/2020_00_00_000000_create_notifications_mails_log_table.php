<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNotificationsMailsLogTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notifications_mails_log', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('rel_id')->nullable();
            $table->string('rel_type')->nullable();
            $table->string('email')->nullable();
            $table->string('user_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('notifications_mails_log');
    }
}
