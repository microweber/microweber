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
        if (!Schema::hasTable('stats_referrers_paths')) {
            Schema::create('stats_referrers_paths', function (Blueprint $table) {
                $table->id();
                $table->integer('referrer_domain_id')->nullable();
                $table->string('referrer_path')->nullable();
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
        Schema::dropIfExists('stats_referrers_paths');
    }
};
