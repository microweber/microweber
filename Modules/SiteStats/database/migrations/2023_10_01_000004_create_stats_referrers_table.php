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
                $table->text('referrer');
                $table->string('referrer_hash');
                $table->integer('referrer_domain_id');
                $table->integer('referrer_path_id');
                $table->integer('is_internal');
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
