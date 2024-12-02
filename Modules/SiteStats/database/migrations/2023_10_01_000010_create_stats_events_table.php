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
        if (!Schema::hasTable('stats_events')) {
            Schema::create('stats_events', function (Blueprint $table) {
                $table->id();
                $table->string('event_category')->nullable();
                $table->string('event_action')->nullable();
                $table->string('event_label')->nullable();
                $table->integer('event_value')->nullable();
                $table->string('utm_source')->nullable();
                $table->string('utm_medium')->nullable();
                $table->string('utm_campaign')->nullable();
                $table->string('utm_term')->nullable();
                $table->string('utm_content')->nullable();
                $table->string('utm_visitor_id')->nullable();
                $table->text('event_data')->nullable();
                $table->dateTime('event_timestamp')->nullable();
                $table->string('session_id')->nullable();
                $table->string('user_id')->nullable();
                $table->integer('is_sent')->nullable();
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
        Schema::dropIfExists('stats_events');
    }
};
