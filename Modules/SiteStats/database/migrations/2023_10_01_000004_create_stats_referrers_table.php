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
        if (!Schema::hasTable('stats_referrers')) {
            Schema::create('stats_referrers', function (Blueprint $table) {
                $table->id();
                $table->text('referrer')->nullable();
                $table->string('referrer_hash')->nullable();
                $table->integer('referrer_domain_id')->nullable();
                $table->integer('referrer_path_id')->nullable();
                $table->integer('is_internal')->nullable();
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
        Schema::dropIfExists('stats_referrers');
    }
};
