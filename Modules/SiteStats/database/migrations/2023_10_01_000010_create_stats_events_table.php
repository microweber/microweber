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
                $table->string('event_category');
                $table->string('event_action');
                $table->string('event_label');
                $table->integer('event_value');
                $table->string('utm_source');
                $table->string('utm_medium');
                $table->string('utm_campaign');
                $table->string('utm_term');
                $table->string('utm_content');
                $table->string('utm_visitor_id');
                $table->text('event_data');
                $table->dateTime('event_timestamp');
                $table->string('session_id');
                $table->string('user_id');
                $table->integer('is_sent');
                $table->dateTime('created_at');
                $table->dateTime('updated_at');
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
