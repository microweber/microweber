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
            $table->increments('id');
            $table->string('type');
            $table->string('notifiable_type');
            $table->string('notifiable_id');
            $table->string('html');
            $table->timestamps();
        });
    }

     
}
